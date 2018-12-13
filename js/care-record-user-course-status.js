/**
* Add, remove or modify
* course or webinar status reports
*/
(function($) {
   $(document).ready(function(){
    console.log( 'Course Progress:tableclass="%s"'
               , care_userprofile_course.tableclass);
     console.log(care_userprofile_course);

     let course = '';
     let courseId = 0;
     let startdate = '1970-01-01';
     let enddate = startdate;
     let location = 'online';
     let watchedPct = 0.0;
     let status = care_userprofile_webinar.statusvalues[0];
     let statusSelect = '<select id="statusSelect" name="status">';
     care_userprofile_webinar.statusvalues.forEach(function(status) {
         statusSelect += '<option>' + status + '</option>';           
      });
      statusSelect += '</select>'

       var sig = '#care-resultmessage';
       var longtimeout = 60000;
       var shorttimeout = 15000;
       let remove_title = 'Remove';
    //    let messwindow = '<div id="caremessagewindow" style="position: absolute; top: 100px; width: 100px; height: 20px; background-color: green"></div>';
       let removeButton = "<button id='remove-course' name='remove-course' type='button'>" + remove_title + "</button>";

       $(sig).addClass('care-error').html(care_userprofile_course.message);
       setTimeout(function(){
                    $(sig).html('');
                    $(sig).removeClass('care-error');
                }, shorttimeout);

       $('#course-select').change(function(e) {
            console.log("#course-select fired!");
            course = e.target.options[e.target.selectedIndex].text;
            courseId = e.target.value; 
            status = pending;
            if(courseId !== '0' ) {
                duplicate = false;          
                $("table.course-status > tbody > tr[id!='add']").each( function() {
                    id = $(this).children('td:nth-child(1)').attr('id');
                    console.log("testing id=%s",id);
                    if( courseId === id ) {
                        console.log("Detected duplicate: %d", courseId );
                        duplicate = true;   
                    }
                });
                if( !duplicate ) {
                    let markup = '<tr id="' + courseId + '">';
                    markup += '<td class="name">' + course + '</td>';
                    markup += '<td class="startdate"><input type="date" id="start" name="startdate" value="' + startdate + '"/></td>';
                    markup += '<td class="status">' + statusSelect + '</td>';
                    markup += '<td class="operation">';
                    markup += '</td></tr>';
                    $("table." + care_userprofile_course.tableclass + " tbody").append(markup);
                    $("table." + care_userprofile_course.tableclass + " tbody").children('tr:last-child').children('td:last-child').append(removeButton);
                    //$("table." + care_userprofile_webinar.tableclass + " tbody").children('tr:last-child').children('td:last-child').append(toggleButton);

                    hidden = '<input type="hidden" name="statusreports[]" value="' + courseId + "|" 
                                                                                    + course + "|" 
                                                                                    + startdate + "|" 
                                                                                    + enddate + "|" 
                                                                                    + status + "|" 
                                                                                    + watchedPct + "|" 
                                                                                    + location + '">';
                    $(hidden).insertAfter("table." + care_userprofile_course.tableclass);
                }
            }
       });
       
        // Remove row
        $("table.course-status").on("click", "button#remove-course", function() {
            courseId = $(this).closest("tr").attr("id");
            console.log('remove fired: courseId=%s', courseId);
            selector = "input[type='hidden'][value^='" + courseId + "']";
            $(selector).remove();
            $(this).closest("tr").remove();
        });

        
        //Modify startdate of a row; index=2
        $("table." + care_userprofile_course.tableclass).on("change", ".startdate", function(e) {
            console.log('webinar date change fired!');
            webinarId = $(this).closest("tr").attr("id");
            console.log("WebinarId=%d", courseId);
            $dateCell = $(this).closest("td.startdate"); 
            // console.log('Date Cell:');
            // console.log($dateCell);
            // console.log('First child:');
            dateElement = $dateCell.children()[0];
            //console.log(dateElement);
            newDate = dateElement.value;
            //console.log('new date is %s', newDate );
            selector = "input[type='hidden'][value^='" + courseId + "']";
            //console.log( $(selector) );
            oldVal = $(selector).val();
            console.log("oldVal=%s", oldVal);
            //$statusCell.text(newStatus);
            arrVal = oldVal.split("|");
            console.log("newVal=%s", arrVal.join("|"));
            arrVal[2] = newDate;
            $(selector).val(arrVal.join("|"));
       });
       
        //Modify status of a row index=4
        $("table.course-status").on("click", "button#toggle-course-status", function() {
            console.log('toggle fired!');
            courseId = $(this).closest("tr").attr("id");
            console.log("CourseId=%d", courseId);
            $statusCell = $(this).closest("td").prev(); //status cell is just to left of operations cell
            selector = "input[type='hidden'][value^='" + courseId + "']";
            oldVal = $(selector).val();
            $statusCell.text(getNextStatus($statusCell.text()));
            arrVal = oldVal.split("|");
            arrVal[4] = $statusCell.text();
            console.log("newVal=%s", arrVal.join("|"));
            $(selector).val(arrVal.join("|"));
       });
       
   });

   function getNextStatus( currentStatus ) {
        let completed = "Completed";
        let registered = "Registered";
        let pending = "Pending"
        switch(currentStatus) {
            case pending:
                return registered;
            case registered:
                return completed;
            default:
                return pending;
        }
   }
})(jQuery);
