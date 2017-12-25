# newsletter_api_php_task
This is a PHP developer test following the instructions in PHP Developer Task.pdf

Written by Luciano García Bes in PHP 7 using Bootstrap and jQuery.

# Installation
Import vanhack.sql into your MySQL server and set the right configuration in config.php. **Ready to go!**

# Web interface
In the main page (assuming vanhack.localhost is your installation directory) you’ll find some statistics showing number of recipients, offers and vouchers in the DB along with the number of vouchers used in the last 24 hours, 48 hours and 7 days.

In the top navbar you’ll find 3 options: *Home*, *Recipients* and *Offers*.

## Home
Home is the main page with the system statistics.

## Recipients
List of the recipients present in the DB and options to create new recipients, edit or delete existing recipients.

### Create Recipient
To create a recipient just need to insert the name and a valid e-mail address. Newly created recipients won’t have vouchers until a new offer is created.

### Edit Recipient
Editing a recipient won’t modify the recipient’s vouchers.

### Delete Recipient
Deleting a recipient will delete the vouchers for that recipient too. Can’t undo this action, beware.

## Offers
List of offers in the DB and options to create a new offer, edit or delete existing offers.

### Create Offer
To create a new offer just need to insert the offer name, discount and expiration date. When a new offer is created a voucher for each existing recipient will be created with a unique code, and an email will be sent to the recipient.

### Edit Offer
The offer edition lets you edit the offer name, expiration date and discount value. No new vouchers will be created when editing the offers.

### Delete Offer
Deleting a offer also deletes all the vouchers for the offer. Can’t undo this action, use with caution.

## API Endpoint
The API interface has calls to get a listing of the valid vouchers for a recipient, information for a voucher and use a voucher. More information about those calls and example codes can be found in `api_calls.txt` file.

### GET listing
With the recipient email as input it retrieves a list of all the valid vouchers for the recipient. It optionally takes page and results values for paging the results.

### GET voucher
Retrieves voucher code, offer name, discount, expiration date, and used status for a given voucher.

### PATCH voucher
Updates the used date for the voucher with the current date and retrieves the discount value along with the new used date for voucher. If the voucher has expired or the voucher is already used it will retrieve an error message.
