//do the jquery stuff in here

$(function(){

	//submits an upvote for a candidate
	$(".upvotebutton,.downvotebutton").click(function(e){
		//get the candidate id and quiz id associated with the item
		e.preventDefault(); //prevent default method of <a>

		var candidate_id = $(this).attr('data-cid');
		var quiz_id = $(this).attr('data-qid');
		var votetype = '';


		if ($(this).attr('class').search('downvote') != -1){
			voteval = 1;
			votetype = 'downvote';

		}
		else{
			voteval = 0;
			votetype = 'upvote';
		}
		doVote(quiz_id, candidate_id, voteval, $(this));

		mixpanel.track('Vote',
				{
					'Candidate':candidate_id,
					'Vote type':votetype
				}
			);


	});

	//submits a comment for a candidate
	$(".submitcomment").click(function(e){
		e.preventDefault();

		var candidate_id = $(this).attr('data-cid');
		var comment_text = $(".comment" + candidate_id).val();

		var request = $.ajax({
			url: "../../comments/submit/",
			type: "POST",
			data: {candidate_id: candidate_id, comment_text: comment_text},
			dataType: "text",
		});

		request.done(function(msg){
			//alert('Upvote sent');
			//roll the status over +1
			if (msg == "0"){
				alert('Your comment failed to save successfully.');
			}
			else{
				//append html to show that comment appearing (this is faked so we don't specify the username or datetime)
				var rep = "<div class='comment'><span class='postedby'><a href='#'>You</a> </span><span class='ago'>Just now</span><p><span class='comtext'>" + comment_text + "</span></p></div>";
				$(".commentbox" + candidate_id).prepend(rep);
				
			}

			mixpanel.track('Comment',
				{
					'Candidate':candidate_id
				}
			);


		});

		request.fail(function(jqXHR, textStatus) {
			//alert( "Request failed: " + textStatus + jqXHR.status );
			if (jqXHR.status == "403"){
		  		alert('You must be logged in to make a comment.');
			}
		});

	});


	//toggles the visibility of comments
	$(".togglecomments").click(function(e){
		e.preventDefault();
		$(this).next().slideToggle();
	});

	//code for adding another add candidate form on the quiz creation page
	var candidateFormCount = 0;
	$(".addcandidate").click(function(){
		candidateFormCount = candidateFormCount + 1;
		//define the form layout
		var formHtml = '<div class="well createwell"><button type="button" class="close tiny button right secondary removecandidate">&times;</button><fieldset><div class="input text"><label for="Candidate' + candidateFormCount + 'Name">Name</label><input name="data[Candidate][' + candidateFormCount + '][name]" maxlength="300" type="text" id="Candidate' + candidateFormCount + 'Name"/></div><div class="input text"><label for="Candidate' + candidateFormCount + 'ImageUrl">Image Url</label><input name="data[Candidate][' + candidateFormCount + '][image_url]" maxlength="300" type="text" id="Candidate' + candidateFormCount + 'ImageUrl"/></div><button class="uploadfile" type="button">Add image</button><div class="input textarea"><label for="Candidate' + candidateFormCount + 'Description">Description</label><textarea name="data[Candidate][' + candidateFormCount + '][description]" cols="30" rows="6" id="Candidate' + candidateFormCount + 'Description"></textarea></div><div class="input text"><label for="Candidate' + candidateFormCount + 'Url">Url</label><input name="data[Candidate][' + candidateFormCount + '][url]" maxlength="300" type="text" id="Candidate' + candidateFormCount + 'Url"/></div><div class="input text"><label for="Candidate' + candidateFormCount + 'Inchi">Inchi</label><input name="data[Candidate][' + candidateFormCount + '][inchi]" maxlength="300" type="text" id="Candidate' + candidateFormCount + 'Inchi"/></div><div class="input text"><label for="Candidate' + candidateFormCount + 'Smiles">Smiles</label><input name="data[Candidate][' + candidateFormCount + '][smiles]" maxlength="300" type="text" id="Candidate' + candidateFormCount + 'Smiles"/></div></fieldset></div>';

		//append to something
		$(".candidates").append(formHtml);

	});

	$(".commentform").focus(function(){
		$(this).val('');

	});

	$(document).on("click", ".removecandidate", function(){

		//this is where a candidate actually gets removed, select the next object and delete it, as well as itself
		$(this).next().remove();
		$(this).remove();

	});

	$(document).on("click", ".delcomment", function(e){
		e.preventDefault();
		var comment_id = $(this).attr('data-cid'); //get comment id attribute
		var p = $(this).parent();
		removeComment(comment_id, p);
	});

	$(document).on("click", ".uploadfile", function(e){
		filepicker.pick({
		    mimetypes: ['image/*'],
		    container: 'modal',
		    services:['COMPUTER', 'GITHUB', 'SKYDRIVE', 'URL'],
		  },
		  function(InkBlob){
		    //console.log(JSON.stringify(InkBlob));
		    //alert(InkBlob.url);
		    $(e.target).prev().children("input").val(InkBlob.url); //don't change this
		  },
		  function(FPError){
		    console.log(FPError.toString());
		  }
		);

		mixpanel.track('Image upload');

	});

	function doVote(quiz_id, candidate_id, vote_type, obj){

		var request = $.ajax({
			url: "../../quizzes/vote/" + quiz_id,
			type: "POST",
			data: {candidate_id: candidate_id, 'vote_type': vote_type},
			dataType: "text",
		});

		request.done(function(msg){


			if (msg == "7"){
				alert('You have already voted for this candidate.');
			}
			else if (msg == "6"){
				alert('You must be logged in to vote.');
			}
			else{ //everything is probably okay
				//roll the status over +1
				var cval = $(obj).children('span').text();
				$(obj).children('span').text(parseInt(cval) + 1);
			}

		});

		request.fail(function(jqXHR, textStatus) {
			if (jqXHR.status == "403"){
				alert('You must be logged in to vote');
			}
		  	//alert( "Request failed: " + textStatus + jqXHR.status );
		});
	}

	function removeComment(comment_id, parentobj){

		var request = $.ajax({
			url: "../../comments/delete",
			type: "POST",
			data: {comment_id: comment_id},
			dataType: "text"
		});

		request.done(function(msg){
			if (msg == "-1"){
				alert('Failed to delete comment.');
			}
			else{ //everything is probably okay
				//jqueryily remove comment
				$(parentobj).remove();
			}

		});

		request.fail(function(jqXHR, textStatus) {
			if (jqXHR.status == "403"){
				alert('You must be logged in to delete your comment');
			}
		});
	}


});

