# TFPlatform


TFPlatform is the fixed-price marketplace software. Ideal for micro jobs, tasks, errands, etc marketplace where consumers outsource micro tasks or sellers offer micro online and offline services.

## Features

### Online & Offline Jobs

1. Online Job
Seller or buyer can accomplish his/her job completely over net.
Ex: Creating a video, creating a music
2. Offline Job
Seller or buyer needs to physically interact with each other.
Ex: purchasing an item in some location, asking a plumber to come and work in your place.

### Seller

1. Seller can post their online/offline jobs in the site. If the job is online,the seller need to perform his task in site itself.
Ex:Creating a video.
2. If the job is offline,the seller can travel/visit to buyer location to perform his job or ask buyer for come to his location to perform that job.
Ex:Plumbing Work,some item purchase.

### Buyer

1.Buyer can buy the online or offline jobs through the site. If the job is online the job will be performed in site itself.
Ex:Creating a video.
2.If the job is offline,the buyer can travel/visit to seller location to perform that job or ask seller for come to his location to perform his job.
Ex: Tattoo, some item purchase,asking a plumber to come and work in your place.

### Featured Jobs

The job will be display first on the job list before other non featured jobs. Featured jobs are listing in home page also.

### Messages

List all the message which the user received.User can starred the message in inbox and user can filter the starred messages seperately.User can able to reply the message from here itself.

### Job View

Entry hover effect performs image zooming, in addition to this it also provides actions like select as winner to contest holder, report entry and etc., In entry view page, user can easily understand all the details of entry, easily traverse to next or previous entries using slider, choose a desired entry directly using view all, hide/show the discussion panel and perform actions like report abuse, rating etc.,

### Redeliver

Buyer can now request redeliver the job, using Request Improvement Tab present during review status

### Mutual Cancel

Both seller and buyer can agree to drop the order.
Mutual Canceling order anytime during order progress by mutual acceptance by buyer n seller
Buyer will be refunded when canceling is done.

### Dispute

Got a issue about buyer/seller, a great way to managing this is using our Dispute section added for managing dispute b/w users(buyer n seller)

### Requests

This is the improved feature of TJPlatform Suggestions.Users can clearly post what they exactly want. And users who can perform this action can easily add a job special for this request.

### Widget

Widget option is provided to display the banners in the site. Admin can able to display the banners in home page, footer, contest view page, user view page.

### Payment

Sudopay - Using this option, user can pay for purchase, signup etc
Sudopay manage all the gateways like paypal, bitcoin, credit & debit cards, dwolla and wallet.

###  Multi-Language Support

Translation of front end with multilingual support.

### Wallet

Wallet system for seller and buyer

## Getting Started

### Prerequisites

#### For deployment

* MySQL
* PHP >= 5.5.9 with OpenSSL, PDO, Mbstring and cURL extensions
* Nginx (preferred) or Apache

### Setup

* Needs writable permission for `/tmp/` , `/media/` and `/webroot/` folders found within project path
* Database schema 'app/config/sql/TJPlatform_with_empty_data.sql'
* Cron with below:
```bash
# Common
*/2 * * * * /{$absolute_project_path}/app/Console/Command/cron.shh 1 >> /{$absolute_project_path}/app/tmp/error.log 2 >> /{$absolute_project_path}/app/tmp/error.log
```
