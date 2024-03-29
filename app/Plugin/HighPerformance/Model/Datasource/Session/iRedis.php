<?php
/**
 * TJ Platform
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    TJPlatform
 * @subpackage Core
 * @author     Nicolas <nicodele8@gmail.com>
 * @copyright  2018 Nicolas 
 * 
 * 
 */
if (!class_exists('iRedis')){

/**
 * iRedis
 *
 * @package		iRedis
 * @version		1.0
 * @author		Dan Horrigan <http://dhorrigan.com>
 * @license		MIT License
 * @copyright	2010 Dan Horrigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * This code is closely related to Redisent, a Redis interface for the modest.
 * Some code is from Redisent and has the following copyrights:
 *
 * @author Justin Poliey <jdp34@njit.edu>
 * @copyright 2009 Justin Poliey <jdp34@njit.edu>
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

	if ( ! defined('CRLF'))
	{
		define('CRLF', sprintf('%s%s', chr(13), chr(10)));
	}

	class iRedis {

		const ERROR = '-';
		const INLINE = '+';
		const BULK = '$';
		const MULTIBULK = '*';
		const INTEGER = ':';

		protected $connection = false;

		/**
		 * Open the connection to the Redis server.
		 * 
		 * @param	  array	  $config	  the config array
		 * @throws  RedisException
		 */
		public function  __construct(array $config = array())
		{
			$config = array_merge(array('hostname' => 'localhost', 'port' => 6379), $config);
			$this->connection = @fsockopen($config['hostname'], $config['port'], $errno, $errstr);

			if ( ! $this->connection)
			{
				throw new RedisException($errstr, $errno);
			}
		}

		/**
		 * Closes the connection to the Redis server.
		 */
		public function __destruct()
		{
			fclose($this->connection);
		}

		/**
		 * Runs the given command ($name) with any number of arguments.
		 * 
		 * @param	  string   $name  the method (command)
		 * @param	  array	   $args  the method (command) arguments
		 * @return  string   the response
		 *  @throws  RedisException
		 */
		public function __call($name, $args)
		{
			$cmd = $this->buildCommand($name, $args);
			$this->sendCommand($cmd);

			return $this->readReply();
		}

		/**
		 * Builds the given command with any number of arguments.
		 * 
		 * @param	  string   $cmd	  the command
		 * @param	  array	   $args  the arguments
		 * @return  string   the full command
		 */
		public function buildCommand($cmd, $args)
		{
			// Start building the command
			$command = '*'.(count($args) + 1).CRLF;
			$command .= '$'.strlen($cmd).CRLF;
			$command .= strtoupper($cmd).CRLF;

			// Add all the arguments to the command
			foreach ($args as $arg)
			{
				$command .= '$'.strlen($arg).CRLF;
				$command .= $arg.CRLF;
			}

			return $command;
		}

		/**
		 * Sends the given command to the Redis server.
		 * 
		 * @param	  string   $command	  the command to send
		 * @throws  RedisException
		 */
		public function sendCommand($command)
		{
			if ( ! $this->connection)
			{
				throw new RedisException('You must be connected to a Redis server to send a command.');
			}

			fwrite($this->connection, $command);
		}

		/**
		 * Reads in a reply from the Redis server.
		 * 
		 * @return  string  the reply
		 * @throws  RedisException
		 */
		public function readReply()
		{
			if ( ! $this->connection)
			{
				throw new RedisException('You must be connected to a Redis server to send a command.');
			}

			$reply = trim(fgets($this->connection, 512));

			switch (substr($reply, 0, 1))
			{
				case iRedis::ERROR:
				throw new RedisException(substr(trim($reply), 4));
				break;

				case iRedis::INLINE:
				$response = substr(trim($reply), 1);
				break;

				case iRedis::BULK:
				if ($reply == '$-1')
				{
					return null;
				}
				$response = $this->readBulkReply($reply);
				break;

				case iRedis::MULTIBULK:
				$count = substr($reply, 1);
				if ($count == '-1')
				{
					return null;
				}

				$response = array();
				for ($i = 0; $i < $count; $i++)
				{
					$bulk_head = trim(fgets($this->connection, 512));
					$response[] = $this->readBulkReply($bulk_head);
				}
				break;

				case iRedis::INTEGER:
				$response = substr(trim($reply), 1);
				break;

				default:
				throw new RedisException("invalid server response: {$reply}");
				break;
			}

			return $response;
		}

		/**
		 * Reads in a bulk reply from the Redis server.
		 * 
		 * @param	  string   $reply	  the reply
		 * @return  string   the bulk reply
		 * @throws  RedisException
		 */
		protected function readBulkReply($reply)
		{
			if ( ! $this->connection)
			{
				throw new RedisException('You must be connected to a Redis server to send a command.');
			}

			$response = null;

			$read = 0;
			$size = substr($reply, 1);

			while ($read < $size)
			{
				// If the amount left to read is less than 1024 then just read the rest, else read 1024
				$block_size = ($size - $read) > 1024 ? 1024 : ($size - $read);
				$response .= fread($this->connection, $block_size);
				$read += $block_size;
			}
			// Get rid of the CRLF at the end
			fread($this->connection, 2);

			return $response;
		}
	}
	
	if (!class_exists('RedisException')){
		class RedisException extends Exception { }
	}

} // endif class-exists iRedis

/**
 * Redis Session Store for CakePHP
 * 
 * iRedisForRedisSession
 * Subclassed iRedis to change __destruct behavior.
 *
 * @package	  redis_session
 * @subpackage redis_session.support
 */
if (!class_exists('iRedisForRedisSession')){
	class iRedisForRedisSession extends iRedis {
		function __destruct() {
		// don't disconnect yet
		}
		function disconnect() {
			parent::__destruct();
		}
	}
}