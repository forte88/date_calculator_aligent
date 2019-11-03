# Aligent Backend Development Assignment

## The Challenge
Create an API that can be used to:
1. Find out the number of days between two datetime parameters.
2. Find out the number of weekdays between two datetime parameters.
3. Find out the number of complete weeks between two datetime parameters.
4. Accept a third parameter to convert the result of (1, 2 or 3) into one of
seconds, minutes, hours, years.
5. Allow the specification of a timezone for comparison of input parameters from
different timezones.

## Requirments

For this application to operate, the following is required:

- Linux operating system (I used ubuntu 18.04 server)
- Apache Web Server
- Php 7.0+
- Composer Repository Management
- Slim Micro Framework 3.12+

## Installation

- You will need to install Composer locally on your server so that the dependencies can be installed I would recommend following - 
https://getcomposer.org/doc/faqs/how-to-install-composer-programmatically.md
- Retrieve the code from github repository <br />
`cd /path/to/web;`  `git clone https://github.com/forte88/date_calculator_aligent.git`
- Run composer update `composer update` to install dependencies

## Usage
The application utilises a RESTful API to handle requests, using POST methods . The application performs the basic retrieve functions as required by the outlined task.

### Routes

**/days :Post** <br />

Calculates the difference between a start and end date with the option to format the results in years, days, hours, minutes & seconds.

- Parameters 
    - start :datetime ('Y-m-d H:i:s') - POST - start datetime
    - end :datetime ('Y-m-d H:i:s') - POST - end datetime
    - formatted:char(y,d,h,m,s,a) - POST - to display formatted results in desired format
- Example of calculating days between two dates <br />
Default:<br />
`curl -d "start=2019-05-16 19:15:02&end=2019-10-28 13:10:15&formatted=a" -X POST [yourlocalhost]/date_calculator_aligent/days
` <br />
Explicit:<br />
`curl -d "start=2019-05-16 19:15:02&end=2019-10-28 13:10:15&formatted=a" -H "Content-Type: application/x-www-form-urlencoded" -X POST [yourlocalhost]/date_calculator_aligent/days`
<br />
Expected Result:<br />
`{"Years":"0","Days":"164","Hours":"16","Minutes":"55","Seconds":"13"}`

**/weeks :Post** <br />

Calculates the difference between a start and end date in weeks ONLY.

- Parameters 
    - start :datetime ('Y-m-d H:i:s') - POST - start datetime
    - end :datetime ('Y-m-d H:i:s') - POST - end datetime
- Example of calculating days between two dates <br />
Default:<br />
`curl -d "start=2019-05-16 19:15:02&end=2019-10-28 13:10:15" -X POST [yourlocalhost]/date_calculator_aligent/weeks
` <br />
Explicit:<br />
`curl -d "start=2019-05-16 19:15:02&end=2019-10-28 13:10:15" -H "Content-Type: application/x-www-form-urlencoded" -X POST [yourlocalhost]/date_calculator_aligent/weeks`
<br />
Expected Result:<br />
`{"Weeks":23}`

**/weekdays :Post** <br />

Calculates the difference between a start and end date with the option to format the results in years, days, hours, minutes & seconds.

- Parameters 
    - start :datetime ('Y-m-d H:i:s') - POST - start datetime
    - end :datetime ('Y-m-d H:i:s') - POST - end datetime
    - formatted:char(y,d,h,m,s,a) - POST - to display formatted results in desired format
- Example of calculating days between two dates <br />
Default:<br />
`curl -d "start=2019-05-16 19:15:02&end=2019-10-28 13:10:15&formatted=a" -X POST [yourlocalhost]/date_calculator_aligent/weekdays
` <br />
Explicit:<br />
`curl -d "start=2019-05-16 19:15:02&end=2019-10-28 13:10:15&formatted=a" -H "Content-Type: application/x-www-form-urlencoded" -X POST [yourlocalhost]/date_calculator_aligent/weekdays`
<br />
Expected Result:<br />
`{"Years":0,"Days":116,"Hours":17,"Minutes":55,"Seconds":13}`

**/timezone :Post** <br />

Calculates the difference between a start and end date with the option to format the results in years, days, hours, minutes & seconds.

- Parameters 
    - start :datetime ('Y-m-d H:i:s') - POST - start datetime
    - end :datetime ('Y-m-d H:i:s') - POST - end datetime
    - timezone_start :DateTimeZone ('Australia/Adelaide') - POST - start timezone
    - timezone_end :DateTimeZone ('Australia/Adelaide') - POST - end timezone
    - formatted:char(y,d,h,m,s,a) - POST - to display formatted results in desired format
- Example of calculating days between two dates <br />
Default:<br />
`curl -d "start=2019-05-16 19:15:02&end=2019-10-28 13:10:15&timezone_start=Europe/London&timezone_end=Australia/Adelaide&formatted=a" -X POST http://fortedev:8080/date_calculator_aligent/timezone` <br />
Explicit:<br />
`curl -d "start=2019-05-16 19:15:02&end=2019-10-28 13:10:15&timezone_start=Europe/London&timezone_end=Australia/Adelaide&formatted=a" -H "Content-Type: application/x-www-form-urlencoded" -X POST http://fortedev:8080/date_calculator_aligent/timezone`
<br />
Expected Result:<br />
`{"Years":"0","Days":"164","Hours":"16","Minutes":"55","Seconds":"13"}`

## Contact
If you experience any issues with this application, please contact David Parry by emailing davidparry610@gmail.com 