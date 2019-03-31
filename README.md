# Recruitment Application
This is a sample application that uses WebRTC Record to create an interview flow with a video questionnaire. 
# To get started you'll need: 
- A Kaltura VPaaS Account [(Register here)](https://vpaas.kaltura.com/register).
- Your Kaltura account ID (aka partnerID) and API Admin Secret, which can be found in the KMC under Settings > Integration Settings.
- The Kaltura PHP 5 Library which can be found [here](https://github.com/kaltura/KalturaGeneratedAPIClientsPHP/releases)


### This application is split into two: The applicant app and the recruiter app. 
# Applicant 


This app first requests identifying information. The applicant cannot proceed without a name, email address, and valid Linkedin address. Then, the applicant is presented with an interview questionnaire. The questions can be modified in `config.inc`
The applicant has a given amount of time to answer the question with a video recording. This too can be modified in `config.inc`. After each recording, the applicant can view the video, and then continue to the next question or record the current video again. 
When all the videos are recorded, the applicant is shown a playlist of all his/her videos. 

### Services

The Kaltura services being used are: 
- **session** This creates Kaltura Session for the user with the `session.start` action
- **metadata** User info is added to the metadata of each video entry using the Metadata API. 
- **playlist** Entries are added to an applicant playlist, titled with the applicant's name. 
- **status** Checks the status of all uploaded entries to determine whether they are ready to be shown in the playlist 

# Recruiter 

This app allows the recruiter to search through all videos with an applicant's name or email address. Clicking on an applicant in the returned list will display the playlist of their interview videos. 

### Services 
This application uses Kaltura's eSearch API, which allows searching through just a part of the applicant's name, or the entire email address. [Learn more about Kaltura's eSearch](https://blog.kaltura.com/introducing-esearch-the-new-kaltura-search-api/)

# Using this code 

**Firstly, download and extract the [Kaltura PHP5 Client library](http://cdnbakmi.kaltura.com/content/clientlibs/php5_20-03-2018.tar.gz) into your project folder and update the `require_once` directive to point to the client library path.**  
You can use this code as is with a few other changes to `config.inc`. This is where you'll keep all your credentials and important variables. 
- `PARTNER_ID` **(int)**: Your partner ID from KMC > Settings tab 
- `ADMIN_SECRET` **(string)**: Like your password. Can also be found in KMC > Settings 
- `USER_ID` **(string)**: This is the email address you use to log into the KMC. It will be used to access the search API on the Recruiter side of the application.  
- `SERVICE_URL` **(string)**: Can stay as ** https://www.kaltura.com **
- `PLAYER_UICONF_ID` **(int)**: The ID of the player. You can use a player you already have, or create a new one in KMC > Studio. 
- `QUESTIONS` **(int)**: Replace the sample questions to fit your needs 
- `SECONDS` **(int)**: The length of time for the video recording 
- `CATEGORY_ID` **(int)**: Create a new category under KMC > Content > Categories and copy its ID. *Optional: give it necessary permissions by following [these instructions](https://knowledge.kaltura.com/faq/how-manage-categories-specific-end-user-permissions).*

# How you can help (guidelines for contributors) 
Thank you for helping Kaltura grow! If you'd like to contribute please follow these steps:
* Use the repository issues tracker to report bugs or feature requests
* Read [Contributing Code to the Kaltura Platform](https://github.com/kaltura/platform-install-packages/blob/master/doc/Contributing-to-the-Kaltura-Platform.md)
* Sign the [Kaltura Contributor License Agreement](https://agentcontribs.kaltura.org/)

# Where to get help
* Join the [Kaltura Community Forums](https://forum.kaltura.org/) to ask questions or start discussions
* Read the [Code of conduct](https://forum.kaltura.org/faq) and be patient and respectful

# Get in touch
We'd love to hear from you!
You can learn more about Kaltura and start a free trial at: http://corp.kaltura.com    
Contact us via Twitter [@Kaltura](https://twitter.com/Kaltura) or email: community@kaltura.com  

# License and Copyright Information
All code in this project is released under the [AGPLv3 license](http://www.gnu.org/licenses/agpl-3.0.html) unless a different license for a particular library is specified in the applicable library path.   

Copyright © Kaltura Inc. All rights reserved.   
Authors and contributors: See [GitHub contributors list](https://github.com/kaltura/video-recruiting-interveiws-app-sample/graphs/contributors).  
