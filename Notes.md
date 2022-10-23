Task 1 Notes:
--------------------------------------------
	Application Endpoint:
	/api/applications

	Application Endpoint with Filter Example:
	/api/applications?type=nbn

	Application Endpoint with Filter and Custom Response Length Example:
	/api/applications?type=nbn&per_page=20

	Notes:
	- Address formatting wasn't specified in the task, so i have concatened all the relavent strings together as a single field.
	- For the monthly cost, i have returned it as an array in the response, as keeping the cents can be useful on the F/E as well as the formatted version.  Ideally the formatting would entirely be handled on the F/E side and the B/E would just return the cents in my opinion.


Task 2 Notes:
--------------------------------------------
	Notes:
	- Used the database driver for this task (See changes in the .env.example/.env), ideally this would be Redis or SQS instead
	- Initial task is a command job, which can be run using `php artisan schedule:work`
	- I've chunked the response from the scheduled task as an optimization measure given the scale of ABB
	- I've used an individual job for each application, but idealy this would be a Batched Job instead
	- There is the potential for applications to be processed multiple times in the base application.  Ideally there would be another status between the 'order' status and the completed/failed state that stores a processing state so they arent picked up again by the scheduler
	- I would also generally store the response from the third party alongside the collection, in case needed for later reference/error tracking, but i have left that as out of scope for the purposes of this task


Overall Notes:
--------------------------------------------
- The tests are fairly simple, but should give a pretty good idea.
