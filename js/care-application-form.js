(function($) {

    $(document).ready(function() {       
        let sig = '#care-reportmessage';
        console.log("Care Application Form");
        //console.log(care_app_form);

        var longtimeout = 60000;
        var shorttimeout = 15000;
        var selectionText;
        var selectionId;
        var startDate;
        var endDate;

        let ajaxFun = function( ) {
            console.log('Care Application: ajaxFun');
            let reqData =  { 'action': care_app_form.action      
                            , 'security': care_app_form.security
                            , 'user_id' : care_app_form.user_id
                             };
            //console.log( reqData );
            console.log("************************Parameters:");
            console.log( reqData );

                // Send Ajax request with data 
            let jqxhr = $.ajax( { url: care_pass_mgmt.ajaxurl    
                                , method: "POST"
                                , async: true
                                , data: reqData
                                , dataType: 'json'
                        ,beforeSend: function( jqxhr, settings ) {
                            //Disable the 'Done' button
                            $(sig).html('Loading...');
                        }})
                    .done( function( res, jqxhr ) {
                        console.log("done.res:");
                        console.log(res);
                        if( res.success ) {
                            console.log('Success (res.data):');
                            console.log(res.data);
                            //Do stuff with data...
                            $(sig).html( res.data.message );
                            report = generateTable(res.data.returnData,care_pass_mgmt.titles,selectionText,'management-report')
                            $('#' + care_pass_mgmt.reporttarget).append(report);
                        }
                        else {
                            console.log('Done but failed (res.data):');
                            console.log(res.data);
                            var entiremess = res.data.message + " ...<br/>";
                            for(var i=0; i < res.data.exception.errors.length; i++) {
                                entiremess += res.data.exception.errors[i][0] + '<br/>';
                            }
                            $(sig).addClass('care-error');
                            $(sig).html(entiremess);
                        }
                    })
                    .fail( function( jqXHR, textStatus, errorThrown ) {
                        console.log("fail");
                        console.log("Error: %s -->%s", textStatus, errorThrown );
                        var errmess = "Error: status='" + textStatus + "--->" + errorThrown;
                        errmess += jqXHR.responseText;
                        console.log('jqXHR:');
                        console.log(jqXHR);
                        $(sig).addClass('care-error');
                        $(sig).html(errmess);
                    })
                    .always( function() {
                        console.log( "always" );
                        setTimeout(function(){
                                    $(sig).html('');
                                    $(sig).removeClass('care-error');
                                }, shorttimeout);
                    });
            
            return false;
        }
        
        function formatDate( date ) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
        
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
        
            return [year, month, day].join('-');
        }

        //Moving out of Ontario?
        $('#move_where').hide();
       $('input[name="moving_out_of_ontario"]').change(function(e) {
            console.log("moving_out_of_ontario change fired!");
            // selectionText = e.target.options[e.target.selectedIndex].text;
            // selectionId = e.target.value; 
            // console.log("id=%s; title='%s'",selectionId, selectionText);
            console.log( "Value is '%s'", e.target.value );
            if( 'Yes' === e.target.value ) {
                $('#move_where').show();
            }
            else {                
                $('#move_where').hide();
            }
       });
       
       //NNAS Application Submitted
        $('#yes_nnas_report').hide();
        $('#no_nnas_report').hide();
       $('input[name="nnas-submitted"]').change(function(e) {
            console.log("nnas-submitted change fired!");
            // selectionText = e.target.options[e.target.selectedIndex].text;
            // selectionId = e.target.value; 
            // console.log("id=%s; title='%s'",selectionId, selectionText);
            console.log( "Value is '%s'", e.target.value );
            if( 'Yes' === e.target.value ) {
                $('#yes_nnas_report').show();
                $('#no_nnas_report').hide();
            }
            else {                
                $('#yes_nnas_report').hide();
                $('#no_nnas_report').show();
            }
        });
        
        //Received NNAS Assessment
        $('input[name="nnas-report-received"]').change(function(e) {
            console.log("nnas-report-received change fired!");
            // selectionText = e.target.options[e.target.selectedIndex].text;
            // selectionId = e.target.value; 
            // console.log("id=%s; title='%s'",selectionId, selectionText);
            console.log( "Value is '%s'", e.target.value );
            if( 'Yes' === e.target.value ) {
                $('#yes_nnas_report').show();
                $('#no_nnas_report').hide();
            }
            else {                
                $('#yes_nnas_report').hide();
                $('#no_nnas_report').show();
            }
        });

        //Have CNO Assessment Letter(s)
        $('#yes-cno-assessment').hide();
        $('input[name="have-cno-assessment"]').change(function(e) {
            console.log("have-cno-assessment change fired!");
            // selectionText = e.target.options[e.target.selectedIndex].text;
            // selectionId = e.target.value; 
            // console.log("id=%s; title='%s'",selectionId, selectionText);
            console.log( "Value is '%s'", e.target.value );
            if( 'Yes' === e.target.value ) {
                $('#yes-cno-assessment').show();
            }
            else {                
                $('#yes-cno-assessment').hide();
            }
        });

        //Courses taken in Canada
        $('.canadian-courses-taken').hide();
        $('input[name="taken-courses-canada"]').change(function(e) {
            console.log("taken-courses-canada change fired!");
            // selectionText = e.target.options[e.target.selectedIndex].text;
            // selectionId = e.target.value; 
            // console.log("id=%s; title='%s'",selectionId, selectionText);
            console.log( "Value is '%s'", e.target.value );
            if( 'Yes' === e.target.value ) {
                $('.canadian-courses-taken').show();
            }
            else {                
                $('.canadian-courses-taken').hide();
            }
        });
        
        //IELTS
        $('#ielts').hide();
        $('input[name="ielts-taken"]').change(function(e) {
            console.log("ielts-taken change fired!");
            // selectionText = e.target.options[e.target.selectedIndex].text;
            // selectionId = e.target.value; 
            // console.log("id=%s; title='%s'",selectionId, selectionText);
            console.log( "Value is '%s'", e.target.value );
            if( 'Yes' === e.target.value ) {
                $('#ielts').show();
            }
            else {                
                $('#ielts').hide();
            }
        });
        
        //CELBAN
        $('#celban').hide();
        $('input[name="celban-taken"]').change(function(e) {
            console.log("celban-taken change fired!");
            // selectionText = e.target.options[e.target.selectedIndex].text;
            // selectionId = e.target.value; 
            // console.log("id=%s; title='%s'",selectionId, selectionText);
            console.log( "Value is '%s'", e.target.value );
            if( 'Yes' === e.target.value ) {
                $('#celban').show();
            }
            else {                
                $('#celban').hide();
            }
        });

        //CLBA
        $('#clba').hide();
        $('input[name="clba-taken"]').change(function(e) {
            console.log("clba-taken change fired!");
            // selectionText = e.target.options[e.target.selectedIndex].text;
            // selectionId = e.target.value; 
            // console.log("id=%s; title='%s'",selectionId, selectionText);
            console.log( "Value is '%s'", e.target.value );
            if( 'Yes' === e.target.value ) {
                $('#clba').show();
            }
            else {                
                $('#clba').hide();
            }
        });
    }); //ready
 })(jQuery);
        