# MyLaps-infoscreen
Show lap times using data from a AMB or MyLaps decoder. This tool is designed to work offline without internet connection.

Originally it fetched the data from mylaps.com, but after a site change it has been rewritten to fetch data directly from the decoder.

It is designed to work without an internet connection, it has an option to fetch avatars and names from mylaps.com, but it can be disabled for offline use. 

## Modules:
This repository contains only the web frontend which has no value without data.
Data is collected by the module amb-laptimes which fetches passings from a AMB/MyLaps decoder and calculates laptimes.

## Requirements:
* PHP 7.4 or higher
* Composer

## Setup
* Clone the repository:
`git clone https://github.com/datagutten/MyLaps-infoscreen`
* Install dependencies:
`composer install`

Create avatar folder:
mkdir avatars
chown www-data avatars

Go to https://speedhive.mylaps.com/Practice and find the digits in the end of the url