/**
 * Redirect to Event for registration for a course
 */
(function($) {
	$(document).ready( function() {
		console.log('Register by Redirecting to an Event');
		console.log(care_event_obj);

		$('#carecourse_register').on('click', function() {

			// var passRequired = false;
			// if( $(this).siblings('#passwordrequired').length > 0) {
			// 	console.log("Password required!");
			// 	passRequired = true;
			// }
			// else {
			// 	console.log("No password needed.");
			// }


			// var pass = "";
			// if( true === passRequired ) {
			// 	pass = prompt("Unlock registration");
			// 	if(pass !== care_event_obj.pwd) {
			// 		return false;
			// 	}
			// }

			// console.log("Dataset:");
			// console.log(this.dataset);
			var eventdata = {'courseId': this.dataset.courseid
							,'courseName': this.dataset.coursename
							,'sessionLocation': this.dataset.sessionlocation
							,'startDate': this.dataset.startdate
							,'startTime': this.dataset.starttime
							,'endDate': this.dataset.enddate
							,'endTime': this.dataset.endtime
							,'sessionSlug': this.dataset.sessionslug
						};

			console.log("Redirecting to: %s",eventdata.sessionSlug);
			window.careMessageHandler.showMessage("Register by for course: " + this.dataset.coursename);

			window.location.href = eventdata.sessionSlug;

			return false;
		});

	})
})(jQuery);
