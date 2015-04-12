
function showAppreciation(members)
{ 
    $("#appreciated-content").html(unescape(members));
    $("#dlg-appreciated").dialog('open'); 
}

function viewMessage(id, url) {
	request = {'id':id};
	$('#yboard-message').addClass('spinner');
	$('#yboard-message').html('');
	$.post(url, request, function(data) {
		if(data.success == 'yes') {
			$('#yboard-message').removeClass('spinner');
            //clear unread status 
            $('#msg_id_'+id).removeClass(); //remove all classes            
			$("#yboard-message").html(data.html);
		} else {
			$('#yboard-message').removeClass('spinner');
			alert(data.message);
		}
	}, 'json'); 
}

function replyMessage(to) {
	$('#field-yboardmessage-subject').hide(); 
	$('#YBoardMessage_send_to').val(to); 
    $("#dlgPrivateMsg" ).dialog('open');
}

function viewPost(id, url) {
	request = {'id':id};
	$('#yboard-message').toggleClass('spinner');
	$('#yboard-message').html('');
	$.post(url, request, function(data) {
		if(data.success == 'yes') {
			$("#yboard-message").html(data.html);
			$('#yboard-message').toggleClass('spinner');
		} else {
			alert(data.message);
			$('#yboard-message').toggleClass('spinner');
		}
	}, 'json');
}

function deletePost(url) {
	request = {'ajax': '1'}; 
	$.ajax({
		url: url,
		type: "GET",
		dataType: "json",
		data: request
	}).done(function(data){ 
        location.reload();
    });
}


function sendPm(uid) {  
	$('#YBoardMessage_send_to').val(uid); 
    $("#dlgPrivateMsg" ).dialog('open');
}

function sendPMForm() {
	formdata = $('#pm-form').serialize();
	url = $('#pm-url').val();  
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') { 
			alert(data.message);
			$("#dlgPrivateMsg").dialog('close');
            location.reload();
		} else {
			alert(data.message); 
		}
	}, 'json');
}

function reportPost(id) {
	$('#YBoardMessage_post_id').val(id); 
	$('#dlgReportForm').dialog('open');
}

function sendReport() {
	formdata = $('#report-form').serialize();
	url = $('#url').val(); 
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			alert(data.message);
			$("#dlgReportForm").dialog('close');
		} else {
			alert(data.message);
			$("#dlgReportForm").dialog('close');
		}
	}, 'json');
}

function banIp(id, url) {
	url = url + /id/ + id;
	$.get(url);
}

/** Ban User with ID in URL   
 * @param url string
 */
function banUser(bannedBy, url) {
	if(bannedBy != undefined) {
        $("#dlg-ban").dialog("option", "buttons", [ 
            { 
                text: "Close", click: function() { 
                    $( this ).dialog( "close" ); 
                } 
            },
            { 
                text: "Ban", click: function() { 
                    
                    request = {
                        'banned_by':bannedBy,
                        'message':$('#ban-message').val(),
                        'expires':$('#ban-expires').val(),
                    }; 
                    
                    $.post(url, request, function(data) {
                        if(data.success == 'yes') { 
                            location.reload();
                        } else {
                            alert(data.message); 
                        }
                    }, 'json'); 
                } 
            }
        ]).dialog('open');
	}  
} 

/** Lift Ban */
function banLift(url) { 
	$.post(url, function(data) {
		if(data.success == 'yes') { 
            location.reload(); 
		} else {
            alert(data.error); 
		}
	}, 'json'); 
}

/** Show Ban reason */
function banMessage(msg) { 
    $('#dlgBanMsgText').text(msg);
	$('#dlgBanMsg').dialog('open');
}


function refreshTopics(obj, url) {
	$.post(url, {'id': obj.value}, function(data) {
		if(data.success == 'yes') {
			$('#YBoardTopic_merge').html(data.option);
		} else {
			alert(data.message);
		}
	}, 'json');
	return false;
}

function upvotePost(id, author, url) {
	$.post(url, {'author': author, 'id': id}, function(data) {
		if(data.success == 'yes') {
			//$('#upvote_'+id).replaceWith(data.html);
            location.reload();
		}
	}, 'json');
	return false;
}

// Poll functions
function showPoll() {
	togglePoll();
	$('#addPoll').val('yes');
}

function hidePoll() {
	togglePoll();
	$('#addPoll').val('no');
}

function togglePoll() {
	$('#poll-button').toggle();
	$('.poll-form-showhide').toggle();
}

function pollChange(obj) {
	id = obj.id;
	text = obj.value; 
	if(text.length > 0) {
		addChoice();
	} else {
        alert(id);
		removeChoice(id);
	}
}

function addChoice() {
	allfilled = true;
	$('#poll-choices>div').children('input').each(function(){
		if(this.value.length == 0) {
			allfilled = false;
		}
	});
	if(allfilled) {
		last_id = $('#poll-choices input:last').attr('id');
		id = last_id.split('_')[1];
		id++;
		html = '<div class="pad5-top"><input id="choice_'+id+'" type="text" name="choice['+id+']" value="" onchange="pollChange(this)" class="form-control"></div>';
		$('#poll-choices').append(html);
		$('#poll-choices input:last').focus();
	}
}

function removeChoice(id) {
	n = $('#poll-choices').children('input').size();
	if(n > 2) {
		$('#'+id).remove();
		addChoice();
	}
}

function vote(url) {
	formdata = $('#yboard-poll-form').serialize();
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			$("#poll").html(data.html);
		}
	}, 'json');
}

function changeVote(poll_id, url) {
	formdata = {'poll_id':poll_id};
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			$("#poll").html(data.html);
		}
	}, 'json');
}

function editPoll(poll_id, url) {
	formdata = {'poll_id':poll_id};
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			$("#poll").html(data.html);
			/*$('#expiredate').datepicker($.extend({showMonthAfterYear:false},$.datepicker.regional[language],
				{'altField':'#YBoardPoll_expire_date','altFormat':'yy-mm-dd','showAnim':'fold','defaultDate':7,'minDate':1}
			));*/
		}
	}, 'json');
} 

YBoard = {
	topicApprove: function(id, url) {
		$.post(url, function(data) {
			if(data.success == 'yes') { 
                $('#'+id).remove(); 
			} else { 
                alert(data.error);
			}
		}, 'json');
	},
	changeTopic: function(url) {
		formdata = $('#update-topic-form').serialize();
		$.post(url, formdata, function(data) {
			if(data.success == 'yes') {
				$("#dlgTopicForm").dialog('close');
                location.reload();
			} else {
				settings = $('#update-topic-form').data('settings');
                //TODO: Update to yii2
				$.each(settings.attributes, function () {
					$.fn.yiiactiveform.updateInput(this, data.error, $('#update-topic-form'));
				});
			}
		}, 'json');
	},
	toggleForumGroup: function(id, url) {
		if($('#category_'+id).css('display') == 'none') {
			action = 'unset';
		} else {
			action = 'set';
		}
		$('#category_'+id).toggle('fold',{size:1},'fast');
		data = {id:id,action:action};
		$.post(url, data, function(data) {}, 'json');

	},
	updateTopic: function(id, url) { 
        $("#yboard-post-update-id").attr('value', id);
		$.post(url, {'id': id}, function(data) {
			if(data.success == 'yes') { 
				$('#YBoardTopic_id').val(id);
                //from post
				$('#YBoardTopic_forum_id').val(data.forum_id);
				$('#YBoardTopic_title').val(data.title);
				$('#YBoardTopic_locked').val(data.locked);
				$('#YBoardTopic_sticky').val(data.sticky);
				$('#YBoardTopic_global').val(data.global);
				$('#YBoardTopic_approved').val(data.approved);
				$('#YBoardTopic_merge').html(data.option);
				$("#dlgTopicForm").dialog('open');
			} else {
				alert(data.message);
				$("#dlgTopicForm").dialog('close');
			}
		}, 'json');
	},
    
	postUpdateDialog: function(url) {  
		$.post(url, function(data) {
			if(data.success == 'yes') {  
				$('#dlgUpdatePostFormHolder').html(data.html); 
				$("#dlgUpdatePostForm").dialog('open');
			} 
            else {
				alert(data.error);
                $('#dlgUpdatePostFormHolder').html(''); 
				$("#dlgUpdatePostForm").dialog('close');
			}
		}, 'json');
	},
    
	updateModPost: function(url) { 
        for(iname in CKEDITOR.instances)
            CKEDITOR.instances[iname].updateElement();
            
        formdata = $('#create-topic-form').serialize();
		$.post(url, formdata, function(data) {
			if(data.success == 'yes') {  
				$("#dlgUpdatePostForm").dialog('close');
                location.reload();
			} 
            else {
				alert(data.error);
                $('#dlgUpdatePostFormHolder').html(''); 
				$("#dlgUpdatePostForm").dialog('close');
			}
		}, 'json');
	},
    
    postApprove: function(id, url) {
		$.post(url, function(data) {
			if(data.success == 'yes') { 
                $('#'+id).remove(); 
			} else { 
                alert(data.error);
			}
		}, 'json');
	},
    
    viewPost: function(html) { 
        html = $("<div/>").html(html).text();
        $('#dlgapproveMsgText').html(html); 
        $('#dlgapproveMsg').dialog('open'); 
	},
}
