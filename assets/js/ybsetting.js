/** Sorting function
 * @param obj HtmlElement Object
 * @param url string
*/
function Sort(obj, url) {
	var sort = $('#'+obj.id).sortable('serialize');
	$.ajax({
		url: url,
		type: "POST",
		dataType: "json",
		data: sort,
		success: function(data){
		},
		error: function(error){
			 console.log("Error:");
			 console.log(error);
		}
	});
}

//Settings
function newSetting(url)
{
    $("#dlgNewSetting").dialog();
    $("#dlgNewSetting").dialog('open');
}

function updateSetting(url, key, value)
{    
    $("#yboard-setting-key").val(key);
    $("#yboard-setting-value").val(value);
    
    $("#dlgNewSetting").dialog({ 
        buttons: [
            { 
                text: "Cancel", click: function(){ 
                    $(this).dialog( "close" ); 
                    location.reload();
                } 
            } ,
            {  
                text: "Save", click: function() { 
                    var formdata = $('#yboard-setting-form').serialize();
                    $.post(url, formdata, function(data) {
                        if(data.success == 'yes') { 
                            location.reload();
                        } else {
                            alert(data.error); 
                        }
                    }, 'json');
                } 
            }  
        ] 
    });
    
    $("#dlgNewSetting").dialog('open');
            
}

function saveSetting(url)
{
    $("#dlgNewSetting").dialog();
    var formdata = $('#yboard-setting-form').serialize();
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			$("#dlgNewSetting").dialog('close');
			location.reload();
		} else {
            alert(data.error);
			//settings = $('#edit-forum-form').data('settings');
			//$.each(settings.attributes, function () {
				//$.fn.yiiactiveform.updateInput(this, data.error, $('#edit-forum-form'));
			//});
		}
	}, 'json');
}

/** Open dlgEditForum for editing Forum
 * @param id integer 
 * @param val string
 */
function editForum(id, val, url) { 
	var request = {'id':id};
	$("#dlgEditForum").dialog('option', 'title', val);
	$('.YBoardForum_type').show();
	$('.YBoardForum_category').show();
	$('.YBoardForum_public').show();
	$('.YBoardForum_locked').show();
	$('.YBoardForum_moderated').show(); 
	$('.YBoardForum_polls').show();  
    
	$.getJSON(url, request, function(data){ 
		$('#YBoardForum_id').val(data.id);
		$('#YBoardForum_name').val(data.name);
		$('#YBoardForum_subtitle').val(data.subtitle);  
		$('#YBoardForum_sort').val(data.sort);  
        $('#YBoardForum_category').val(data.cat_id);  
		$('#YBoardForum_public').val(data.public);
		$('#YBoardForum_locked').val(data.locked);
		$('#YBoardForum_moderated').val(data.moderated);
		$('#YBoardForum_type').val(data.type);
		$('#YBoardForum_membergroup_id').val(data.membergroup_id);
		$('#YBoardForum_polls').val(data.poll);
	});
	$("#dlgEditForum").dialog('open');
}

/** Delete Forum or Category */
function deleteForum(url) {
	var val = confirmation[$('#YBoardForum_type').val()];
	var id = $('#YBoardForum_id').val();
	var request = {'id':id};
	if(confirm(val)) {
		$.post(url, request, function(data) {
			if(data.success == 'yes') {
				$("#dlgEditForum").dialog('close');
				location.reload();
			} else {
				alert(data.message);
			}
		}, 'json');
	}
}

/** Save Forum or Category */
function saveForum(url) {
	var formdata = $('#edit-forum-form').serialize();
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			$("#dlgEditForum").dialog('close');
			location.reload();
		} else {
            console.log(data.error);
            alert(data.error);			 
		}
	}, 'json');
}

/** Open dlgEditForum for editing Category
 * @param id integer 
 * @param val string
 */
function editCategory(id, val, url) {
	var request = {'id':id};
	$("#dlgEditForum").dialog('option', 'title', val);
	//$('.YBoardForum_category').hide();
	$('.YBoardForum_public').hide();
	$('.YBoardForum_locked').hide();
	$('.YBoardForum_moderated').hide();  
	$('.YBoardForum_polls').hide();  
    
	$.getJSON(url, request, function(data){
		$('#YBoardForum_id').val(data.id);
		$('#YBoardForum_name').val(data.name);
		$('#YBoardForum_category').val(data.category);
		$('#YBoardForum_category').val(data.category);
		$('#YBoardForum_sort').val(data.sort);
		$('#YBoardForum_type').val(data.type); //the ID of parent Forum?
 	});
	$("#dlgEditForum").dialog('open');
}

/** Open dlgEditMembergroup for creating or editing Membergroup
 * @param id integer 
 * @param url string
 */
function editMembergroup(id, url) {
	var request = {'id':id};
	if(id == undefined) {
			$('#YBoardMembergroup_name').val('');
			$('#YBoardMembergroup_description').val(''); 
			$('#YBoardMembergroup_image').val('');
			$('#YBoardMembergroup_group_role').val('');
			$("#dlgEditMembergroup").dialog('open');
	} else {
		$.getJSON(url, request, function(data){ 
			$('#YBoardMembergroup_id').val(data.id);
			$('#YBoardMembergroup_name').val(data.name);
			$('#YBoardMembergroup_description').val(data.description); 
			$('#YBoardMembergroup_image').val(data.image);
			$('#YBoardMembergroup_group_role').val(data.group_role);
            $("#YBoardMembergroup_color").spectrum("set", data.color);
		}); 
		$("#dlgEditMembergroup").dialog('open');
	}
}

/** Delete Membergroup */
function deleteMembergroup(url) {
	var id = $('#YBoardMembergroup_id').val();
	var request = {'id':id};
	if(confirm(confirmation)) {
		$.post(url, request, function(data) {
			if(data.success == 'yes') {
				$("#dlgEditMembergroup").dialog('close');
				location.reload();
			} else {
				alert(data.message);
			}
		}, 'json');
	}
}

/** Save Membergroup */
function saveMembergroup(url) {
	var formdata = $('#edit-membergroup-form').serialize();
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			$("#dlgEditMembergroup").dialog('close');
            location.reload(); 
		} else {
            alert(data.error);
			/*settings = $('#edit-membergroup-form').data('settings');
			$.each(settings.attributes, function () {
				$.fn.yiiactiveform.updateInput(this, data.error, $('#edit-membergroup-form'));
			});*/
		}
	}, 'json'); 
}

/** Delete Member*/
function deleteMember(url) {  
    $.post(url, function(data) {
        if(data.success == 'yes') { 
            alert('Done!');
            location.reload();
        } else {
            alert(data.error);
        }
    }, 'json'); 
}

/** Open dlgEditRank for creating or editing Ranks
 * @param id integer 
 * @param url string
 */
function editRank(id, url) { 
	var request = {'id':id};
	if(id == undefined) {
			$('#YBoardRank_title').val('');
			$('#YBoardRank_min_posts').val(''); 
			$('#YBoardRank_stars').val('');
			$("#dlgEditRank").dialog('open');
	} else {
		$.getJSON(url, request, function(data){ 
			$('#YBoardRank_id').val(data.id);
			$('#YBoardRank_title').val(data.title);
			$('#YBoardRank_min_posts').val(data.min_posts); 
			$('#YBoardRank_stars').val(data.stars);
		}); 
		$("#dlgEditRank").dialog('open');
	}
}

/** Delete Rank */
function deleteRank(url) {
	var id = $('#YBoardRank_id').val();
	var request = {'id':id};
	if(confirm(confirmation)) {
		$.post(url, request, function(data) {
			if(data.success == 'yes') {
				$("#dlgEditRank").dialog('close');
				location.reload();
			} else {
				alert(data.message);
			}
		}, 'json');
	}
}

/** Save Rank */
function saveRank(url) {
	var formdata = $('#edit-rank-form').serialize();
	$.post(url, formdata, function(data) {
		if(data.success == 'yes') {
			$("#dlgEditRank").dialog('close');
            location.reload(); 
		} else {
            alert(data.error);
			/*settings = $('#edit-membergroup-form').data('settings');
			$.each(settings.attributes, function () {
				$.fn.yiiactiveform.updateInput(this, data.error, $('#edit-membergroup-form'));
			});*/
		}
	}, 'json'); 
}

var YBoardSetting = {
	EditSpider: function(id,url) {
		var request = {'id':id};
		if(id == undefined) {
				$('#YBoardSpider_name').val('');
				$('#YBoardSpider_user_agent').val('');
				$("#dlgEditSpider").dialog('open');
		} else {
			$.getJSON(url, request, function(data){
				$('#YBoardSpider_id').val(data.id);
				$('#YBoardSpider_name').val(data.name);
				$('#YBoardSpider_user_agent').val(data.user_agent);
			});
			$("#dlgEditSpider").dialog('open');
		}
	},
	DeleteSpider: function(url) {
		var id = $('#YBoardSpider_id').val();
		var request = {'id':id};
		if(confirm(confirmation)) {
			$.post(url, request, function(data) {
				if(data.success == 'yes') {
					$("#dlgEditSpider").dialog('close'); 
                    location.reload();
				} else {
					alert(data.message);
				}
			}, 'json');
		}
	},
	SaveSpider: function(url) {
		var formdata = $('#edit-spider-form').serialize();
		$.post(url, formdata, function(data) {
			if(data.success == 'yes') {
				$("#dlgEditSpider").dialog('close');
                location.reload();
			} else {
                alert(data.message);
			}
		}, 'json');
	}
}
