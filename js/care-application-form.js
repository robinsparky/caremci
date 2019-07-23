(function($) {
    $(document).ready(function() {       
        console.log("Care Application Form");

        //Moving out of Ontario?
        $('#move_where').hide();
       $('input[name="moving_out_of_ontario"]').change(function(e) {
            //console.log("moving_out_of_ontario change fired!");
            if( 'Yes' === e.target.value ) {
                $('#move_where').show();
            }
            else {                
                $('#move_where').hide();
            }
       });
       
        //NNAS Application Submitted
        $('#yes-submitted-nnas').hide();
        $('input[name="nnas-submitted"]').change(function(e) {
            //console.log("nnas-submitted change fired!");
            if( 'Yes' === e.target.value ) {
                $('#yes-submitted-nnas').show();
            }
            else {                
                $('#yes-submitted-nnas').hide();
            }
        });
        
        //Received NNAS Assessment
        $('#yes_nnas_report').hide();
        $('#no_nnas_report').hide();
        $('input[name="nnas-report-received"]').change(function(e) {
            //console.log("nnas-report-received change fired!");
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
            //console.log("have-cno-assessment change fired!");
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
            //console.log("taken-courses-canada change fired!");
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
            //console.log("ielts-taken change fired!");
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
            //console.log("celban-taken change fired!");
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
            // console.log("clba-taken change fired!");
            // console.log( "Value is '%s'", e.target.value );
            if( 'Yes' === e.target.value ) {
                $('#clba').show();
            }
            else {                
                $('#clba').hide();
            }
        });
    }); //ready
 })(jQuery);
        