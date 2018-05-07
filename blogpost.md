# Save time and awkward interactions with applicant video interviews 

As a hiring manager or recruiter, your inbox is constantly full of resumes, and only 2% of these applicants will really be relevant [according to Glassdoor](https://www.glassdoor.com/employers/popular-topics/hr-stats.htm). Hours spent reviewing text or LinkedIn profiles, followed by hours of meeting the wrong candidates... It's frustrating, unproductive, and eats at a lot of your time. 

Consider being on an urgent hiring spree for a new project. After tediously looking thrugh hundreds of CVs, you find this one guy, Ryan. You're sure he's your winner. You call; he passes the technical call with flying colors, and you schedule an interview. 
But chatting with Ryan is worse than watching paint dry. This guy cannot be your second in command. There go four days of your precious time, and sadly, it's back to the drawing board. 

What if you could ensure company culture fit within minutes - before wasting hours of time on interviews - by watching recorded videos of your applicants answering questions? More important than the content of their answers is the general personality or *vibe*. Imagine sifting through candidates for sales roles by watching videos of their 60 second sales pitch. Or, alternatively, get to know with video how your applicants would react to certain situations by presenting them with [situational judgement tests](https://www.changeboard.com/article-details/16255/situational-judgement-tests-the-future-of-recruitment-in-a-digital-world/). 
There couldn't be a better way to save time and energy for you and the applicant throughout the painful interview process. [Here's](https://github.com/kaltura/video-recruiting-interveiws-app-sample) the sample application we created to show you how easy it is.

## The Basics 

The idea is simple, really. The applicant answers a couple questions over video, using a WebRTC in-browser recorder. You decide how long the answers can be. We went with thirty seconds and some fairly basic questions: 

1. Tell us about yourself
2. Why do you think you'll be a good fit for this role? 
3. What can you contribute to Kaltura?
4. Where would you want to see your career in five years?

The applicant has the option of trying again and again. Once he/she is satisfied, the video gets uploaded, along with metadata, and added to a playlist. When the questions are complete, the playlist is displayed for the applicant to endure further embarrassment. 

And you? You get to watch all the videos from the comfort of your couch, popcorn included. The easier, quicker way of determining who to actually spend time on. Yes, videos take time to watch, but you can watch 10 seconds and be certain that you are *not* going to spend late nights with this individual. 

## User management with kuser accounts 

All Kaltura API actions require a kaltura session, which is an authorization string that tells us who you are. The applicant begins the interview by inputting his/her name, email address, and linkedin url. We use that email address to create a Kaltura User, and then create a session with that same User. This way, when applicant videos are uploaded, they are created with the applicant's user info, giving us the ability to search for them later. 


## Keeping things organized with custom metadata  

Custom Metadata allows you to add special fields and data to an entry. In this case, the application creates a Custom Metadata Profile called ApplicantDetails and then loads the profile's xml schema. Upon video upload, the email, name, and linkedin fields are populated with their values and added to the entry. Kaltura's new [search API](https://blog.kaltura.com/introducing-esearch-the-new-kaltura-search-api/) makes it easy to search through that metatadata content. 

## The Playlist: your applicant's own mixtape 

We check if the applicant's playlist exists. If it doesn't, we create one titled with the full name, and set the creator as the email address. We also add it to a specific category - we chose `recruiterApplication`. Then we add the entry (titled with the question number) to the playlist and hold onto the playlist ID. We'll need that later. 

## Ready?

When the interview questions are complete, and before we can show the playlist, we need to make sure that all entries are ready. Depending on the lenght of the videos, this can take a few minutes. We set an interval timer that checks the status of the entries every 10 seconds. When all entries are ready to be played, the playlist is embedded using the playlist ID that we mentioned earlier. We showed a spinner during the wait time, but you can play a cute cat video if you want. Your potential developers would like that. 

## Your turn to browse

Here's the fun part. You or your team of recruiters can log into the recruiter application and search through all your applicant playlists. [Kaltura eSearch](https://blog.kaltura.com/introducing-esearch-the-new-kaltura-search-api/) allows you to search with the applicant's full email address, or a partial match of his/her name. We show you a list of entries with user details, and clicking on an entry... you guessed it... will show you the playlist. 
But wait, there's a catch. How do we protect your candidates' embarrassing moments from being viewed by everyone and his brother? 

## Confidentiality and privacy 

Remember that `recruiterApplication` category we created? It isn't just about keeping your entries organized. With category entitelments, you allow only specific users to view the applicant playlists. This can be done via our API or in the KMC under category settings. 
Upon login to the application, we create a Kaltura Session for the user, which will only return search results if the user is a member of that category.  Now you can sleep soundly knowing that your recruiters - and your recruiters only - will be subject to all those minutes of sales pitch fun!

## So what else can you do? 

You can give the applicant the option of creating a password so he/she can save current progress and come back later to continue. 
You can add a file upload for a resume or cover letter. You can get really creative and use Kaltura Reach to transcribe the video and then search for specific words or topics within your applicants' interview videos. Or use voice analysis to determine how the candidate's voice makes people feel. The possibilities are endless! 
