(function($) {

    if (!String.prototype.startsWith) {
        Object.defineProperty(String.prototype, 'startsWith', {
            value: function(search, rawPos) {
                pos = rawPos > 0 ? rawPos|0 : 0;
                return this.substring(pos, pos + search.length) === search;
            }
        });
    }

    $(document).ready(function() {       
        let sig = '#care-reportmessage';
        console.log("Landing Page");
        var longtimeout = 60000;
        var shorttimeout = 15000;
        var selectionText;
        var selectionId;
        var startDate;
        var endDate;

        let ajaxFun = function( ) {
            console.log('Management Reports: ajaxFun');
            let reqData =  { 'action': care_pass_mgmt.action      
                            , 'security': care_pass_mgmt.security
                            , 'user_id' : care_pass_mgmt.user_id
                            , 'id': selectionId
                            , 'report_start': startDate
                            , 'report_end': endDate };
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

        function generateTable(rowsData, titles, caption, _class) {
            var $table = $("<table>").addClass(_class);
            var $capt   = $("<caption>").appendTo($table);
            $capt.html(caption);
            var $tbody = $("<tbody>").appendTo($table);
            type=1;
            
            if (type == 2) {//vertical table
                if (rowsData.length !== titles.length) {
                    console.error('rows and data rows count do not match');
                    return false;
                }
                titles.forEach(function (title, index) {
                    var $tr = $("<tr>");
                    $("<th>").html(title).appendTo($tr);
                    var rows = rowsData[index];
                    rows.forEach(function (html) {
                        $("<td>").html(html).appendTo($tr);
                    });
                    $tr.appendTo($tbody);
                });
                
            } else if (type == 1) {//horizontal table 
                var valid = true;
                rowsData.forEach(function (row) {
                    if (!row) {
                        valid = false;
                        return;
                    }
        
                    if (row.length !== titles.length) {
                        valid = false;
                        return;
                    }
                });
        
                if (!valid) {
                    console.error('rows and data rows count doe not match');
                    return false;
                }
        
                var $tr = $("<tr>");
                titles.forEach(function (title, index) {
                    $("<th>").html(title).appendTo($tr);
                });
                $tr.appendTo($tbody);
        
                rowsData.forEach(function (row, index) {
                    var $tr = $("<tr>");
                    row.forEach(function (html) {
                        $("<td>").html(html).appendTo($tr);
                    });
                    $tr.appendTo($tbody);
                });
            }
        
            return $table;
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

        function addCloseLink(content) {
            content = content || "close";
            var $link = $("<a id='overlaycloser'>").addClass("landingcloser");
            $link.html(content);
            $('body').append($link);
            $link.on('click', function() {
                console.log('closer!');
                window.parent.jQuery('iframe.care-overlay').attr('src','').hide();
            });
        }

        function inIframe() {
            try {
                return window.self !== window.top;
            } catch (e) {
                return true;
            }
        }

        if( inIframe() ) {
            console.log("in frame");
            $('a').attr("target","_blank");//disable all links
            //$('a').parents().hasClass('star-nav').removeAttr('target');

            //Force use of green text for these headers
            $('h2, h3').addClass('landingheader');

            addCloseLink();
        }
        else {
            console.log("not in frame");
            $('#menu-about-us a').on('click', function( evt ) {
                evt.preventDefault();
                let url = $(this).attr("href");
                console.log("Setting iframe src to %s", url);
                if( !url.startsWith("#") ) {
                    $('iframe.care-overlay').attr('src', url );
                    $('iframe.care-overlay').show();
                }
            })
            $('body').on('click', function( evt ) {
                if( !$(evt.target).parents().hasClass('star-nav')) {
                    $('iframe.care-overlay').attr('src','').hide();
                }
            });
        }
    });
 })(jQuery);
        