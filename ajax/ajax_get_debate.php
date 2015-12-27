<?php
	@include_once "../config.php";
	$newsID = $database->cleanXSS($_GET["id"]);
	$newsRstArr = $news->GetDetails($newsID);
	$newsAllVotedRstArr = $news->DisplayAllVotedDetails(1,10000000000,$newsID);
	$newsThoughtRstArr = $news->DisplayAllThoughts($newsID);
	$userVoted = $news->UserVoted($newsID);
	
	
	if(is_array($newsRstArr) && count($newsRstArr) > 0)
	{
		$news->ViewPage($newsID);
	}
	
	$agreeAmt = $disagreeAmt = 0;
	
	if(is_array($newsAllVotedRstArr["List"]) && count($newsAllVotedRstArr["List"]) > 0)
	{
	
		foreach($newsAllVotedRstArr["List"] as $r2ID => $r2Value)
		{
			if($r2Value["voteType"] == "agree")
				$agreeAmt++;
				else
					$disagreeAmt++;
	
		}
	}
	$totalagreement = $agreeAmt + $disagreeAmt;
	
	$agreePercent = 0;
	if($totalagreement > 0)
		$agreePercent = ceil(($agreeAmt/$totalagreement) * 100);
	
	
		$disagreePercent = 100 - $agreePercent;
		if(!$newsAllVotedRstArr)
			$disagreePercent = 0;
	
	
			$novotes = false;
			if($disagreePercent == 0 && $agreePercent == 0)
			{
				$novotes = true;
			}
	
	
			$moreNewsRst = $news->DisplayMoreDetails($newsRstArr["categoryID"],$newsID);
			?>
	
	<section class="debate-move">
		<div class="debate-title">
			<div class="row">
				<div class="twelve columns" id="debate-title-n-menu">
					<a href="<?php echo $GLOBAL_WEB_ROOT ?>" class="debate-slideover-back">
						<i class="icomo-back"></i>
						<span><?php echo "Home"/*$newsRstArr["categoryName"]*/?></span>
					</a>
						<span>|</span> 
					<!--a href="news/<?php echo $newsRstArr["newsID"] ?>/<?php echo $newsRstArr["newsPermalink"] ?>"-->
					<a href="<?=(false===stripos($newsRstArr["newsSource"],"http")?"http://".$newsRstArr["newsSource"]:$newsRstArr["newsSource"])?>" target="_blank">
						<span><?php echo (strlen($newsRstArr["newsTitle"]) > 50)? substr($newsRstArr["newsTitle"], 0,50)."...":$newsRstArr["newsTitle"];?></span>
					</a>
	                <?php                
	                if(@$_SESSION["userID"] > 0)
	                {
	                    echo '
	                    		<span class="greetuser">Hi <a href="user_moderation.php" id="debate-greetuser-anchor">'.$_SESSION["fullname"].'</a></span>
								<a href="javascript:;" class="logout-btn-debate" title="logout"><i class="icomo-logout"></i></a>
							';
	                }
	                else
	                {
	                    echo '
	                            <div class="debate-login-signup">
								<a href="javascript:;" class="switchpopup" data-to="registration-popup">Join Now</a>
								<span>/</span>
								<a href="javascript:;" class="switchpopup" data-to="login-popup">Login</a>
	                            </div>
								';
	                }
	                ?>
				</div>
			</div>
		</div>
	
		<div class="debate-slideover-wrapper clearfix">
			<div class="row">
				<div class="eight columns centered text-center">
				    <div>
				    <!--a href="news/<?php echo $newsRstArr["newsID"] ?>/<?php echo $newsRstArr["newsPermalink"] ?>"-->
				    <a href="<?=(false===stripos($newsRstArr["newsSource"],"http")?"http://".$newsRstArr["newsSource"]:$newsRstArr["newsSource"])?>" target="_blank">
				      <img src="uploads/banner/thumbnail/<?=$newsRstArr['newsBanner']?>">
				    </a>
				    </div>
					<img src="img/quote-mark-open.png" />
				    <!--a href="news/<?php echo $newsRstArr["newsID"] ?>/<?php echo $newsRstArr["newsPermalink"] ?>"-->
				    <a href="<?=(false===stripos($newsRstArr["newsSource"],"http")?"http://".$newsRstArr["newsSource"]:$newsRstArr["newsSource"])?>" target="_blank">
					<h1 class="debate-slideover-question">
						<?php echo $newsRstArr["newsQuestion"]?>
					</h1>
				    </a>
					<img src="img/quote-mark-close.png" />
	
	
					<div class="debate-sliderover-decision">
						<div class="row">
							<div class="twelve columns text-center">
								<a href="javascript:;" <?php 
									if (@$_SESSION["userID"]==0 && $user->getAnonymousID()==0)
										echo 'id="login-btn-agree"'; 
								?> data-newsID="<?php echo $newsID?>" class="agree-btn <?php 
									if (@$_SESSION["userID"] == 0 && $GLOBAL_ALLOW_ANONYMOUS == 0
											|| @$userVoted["voteType"] == "disagree")
										echo "disabled";
								?> <?php 
									if (@$userVoted["voteType"] == "agree")
										echo "selected";
								?>"><i class="icomo-yes"></i></a>
								<a href="javascript:;" <?php
									if (@$_SESSION["userID"]==0 && $user->getAnonymousID()==0)
										echo 'id="login-btn-disagree"'; 
								?> data-newsID="<?php echo $newsID?>" class="disagree-btn <?php
									if (@$_SESSION["userID"] == 0 && $GLOBAL_ALLOW_ANONYMOUS == 0
											|| @$userVoted["voteType"] == "agree")
										echo "disabled";
								?> <?php 
									if (@$userVoted["voteType"] == "disagree")
										echo "selected";
								?>"><i class="icomo-no"></i></a>
							</div>
						</div>
					</div>
	
					<!-- div class="row">
						<div class="twelve columns text-center">
							Click below link to view full story:
						</div>
						<div class="twelve columns text-center-underline">
							<a href="news/<?php echo $newsRstArr["newsID"] ?>/<?php echo $newsRstArr["newsPermalink"] ?>"><?php echo $newsRstArr["newsTitle"]?></a>
						</div>
					</div-->
	
					<?php
						if(is_array(@$newsThoughtRstArr["List"]) && count(@$newsThoughtRstArr["List"]))
						{
							echo '<ul class="thoughts-list">';
							foreach($newsThoughtRstArr["List"] as $NTId => $NTValue)
							{
								echo '<li>'.$NTValue["thoughts"].'</li>';
							}
							echo '</ul>';
						}
					?>
				</div>
			</div>
	
			<div class="row">
				<div class="twelve columns">
					<div class="join-discussion-ratings <?php echo ($novotes)? "empty":""?>">
						<div class="row">
							<div class="four columns "><h3><?php echo $agreePercent;?>% says yes</h3></div>
							<div class="four columns text-center"><h3>Total <?= $agreeAmt+$disagreeAmt?> people voted</h3></div>
							<div class="four columns text-right"><h3><?php echo $disagreePercent ?>% says no</h3></div>
						</div>
						<div class="join-discussion-filler clearfix">
							<div class="join-discussion-filler-yes" style="width:<?php echo $agreePercent?>%"></div>
							<div class="join-discussion-filler-no" style="width:<?php echo $disagreePercent?>%"></div>
						</div>
					</div>
				</div>
			</div>
	
			<div class="row">
				<div class="twelve columns detate-content-lists">
					<div class="timeline clearfix" style="padding-left:2%;">													
						<div class="yes-debate">					
							<div class="agree-box " <?php echo (@$userVoted["voteType"] == "agree")? "style='display:block'":"";?>>
								<div class="debate-box-agree-dot"></div>
								<div class="debate-box-agree-arrow"></div>
								<form class="debate-reply" name="debate-reply"  data-newsID="<?php echo $newsID ?>">
									<div class="debate-reply-ask">Why Yes? Tell us more.</div>
									<!--div class="">
										
										<label>Your Statement</label>
										<input type="text" name="replyStatement" maxlength="60">
									</div-->
									<div class="">
										<label>Write Your Opinions</label>
										<textarea class="agree-opinion" name="replyDesc"></textarea>
									</div>
									<div class="row ">
										<div class="six columns">
											<div class="agree-charcount">Character limit (<span class="agree-opinion-count"></span>/2000)</div>
										</div>
										<div class="six columns text-right">
											<input type="submit" name="send" value="SUBMIT" class="primarybtn2">
										</div>
										
									</div>
									<input type="hidden" name="newsID" value="<?php echo $newsID?>">
									
									<input type="hidden" name="replyType" value="agree">
									<input type="hidden" name="userID" value="<?php echo @$_SESSION["userID"]?>">
									<input type="hidden" name="displayName" value="<?php echo @$_SESSION["displayName"]?>">								
								</form>
								<div class="debate-notification">
									<div class="debate-notification-title">Thank you for submitting  your opinion.</div>
									<a href="javascript:;" class="primarybtn2 writeagain">Write Again</a>
								</div>
							</div>
							<?php
								$agreeReplyRstArr = $news->DisplayReplyDetails(1,10000,$newsID,"agree");
	
								if(is_array(@$agreeReplyRstArr["List"]) && count(@$agreeReplyRstArr["List"]) > 0)
								{
									foreach($agreeReplyRstArr["List"] as $id =>$agreeArr)
									{
										$agreeLikes = $news->ReplyLikes($agreeArr["replyID"]);
										$agreeLikes = (!$agreeLikes)? array():$agreeLikes["usercontent"];
										
										
							?>
										<div class="debate-box " id="debate-box-<?php echo $agreeArr["replyID"]?>" <?php echo (@$userVoted["voteType"] == "disagree")? "style='display:block'":"";?>>
											<div class="debate-box-agree-dot"></div>
											<div class="debate-box-agree-arrow"></div>																				
											<div class="debate-box-userdetails clearfix">
												<div class="debate-box-userdetails-avatar">
													<?php
	
													if($agreeArr["userProfilePicture"] == "" || $agreeArr["userProfilePicture"] == "undefined")
														{
															echo '<img src="img/replyavatar.jpg">';
														}
														else
														{														
															echo '<img src="uploads/profile/thumbnail/'.$agreeArr["userProfilePicture"].'">';
														}
													?>
												</div>
												<div class="debate-box-userdetails-name">
													<?php
														echo $user->getDisplayNameFromArray($agreeArr);
													?>			
												</div>
												<a href="javascript:;" title="I like!" class="debate-box-userdetails-likes <?php 
														if (@$_SESSION["userID"] == 0 && $user->getAnonymousID() == 0)
															echo "disabled";
													?>" data-replyID="<?php echo $agreeArr["replyID"]?>" data-newsID="<?php echo $newsID?>">
													<i class="icomo-love <?php
														if (in_array(@$_SESSION["userID"],$agreeLikes) ||
																in_array($user->getAnonymousID(),$agreeLikes))
															echo 'selected';
														?>"></i><span><?php echo count($agreeLikes)?></span>
												</a>
												<a href="javascript:;" class="debate-box-userdetails-comment" title="View Reply Details" data-id="<?php echo $agreeArr["replyID"]?>">
													<i class="icomo-reply"></i><?php echo count(@$agreeArr["subreply"])?>
												</a>
												<a href="javascript:;" class="debate-box-userdetails-opinion" data-id="<?php echo $agreeArr["replyID"]?>" title="View Opinion Details"><i class="icomo-recommend"></i>Details</a>
											</div>
											<!--div class="debate-box-usercontent"><strong><?php echo $agreeArr["replyStatement"]?></strong></div-->
											<div class="debate-box-usercontent"><?php echo $agreeArr["replyCreatedDateTime"]?></div>
											<div class="debate-box-usercontent-opinion debate-usercontent-opinion<?php echo $agreeArr['replyID']?>"><?php echo $agreeArr["replyContent"]?></div>
											
											<div class="reply-box reply<?php echo $agreeArr["replyID"]?> clearfix">
												<form class="reply-form text-right">
													<?php 
	
														if(@$_SESSION["userID"] == 0 && $user->getAnonymousID() == 0)
														{
													?>
															<div class="replybox-prohibited">
																<div class="replybox-prohibited-title">Oops. You are not logged in.</div>
																<div class="replybox-prohibited-content">
																	<a href="javascript:;" class="switchpopup" data-to="login-popup">Sign in</a> to Newslogue or <a href="javascript:;" class="switchpopup" data-to="registration-popup">create an account</a> to comment.
																</div>
															</div>
															
													<?php
														}
														else
														{
													?>
															<textarea name="replyDesc" placeholder="Write your reply..." class="reply-textarea"></textarea>
															<input type="hidden" name="newsID" value="<?php echo $newsID?>">
															<input type="hidden" name="replyID" value="<?php echo $agreeArr["replyID"]?>">
															<input type="hidden" name="userID" value="<?php echo @$_SESSION["userID"]?>">
															<input type="hidden" name="newsID" value="<?php echo $newsID?>">
															<input type="hidden" name="replyType" value="agree">
															<input type="hidden" name="send" value="SUBMIT">
															<input type="hidden" name="displayName" value="<?php echo @$_SESSION["displayName"]?>">
															<input type="submit" class="primarybtn" value="Submit">
													<?php
														}
													?>
												</form>
												<div class="reply-listing">
												<?php
													if(is_array(@$agreeArr["subreply"]))
													{
														foreach($agreeArr["subreply"] as $sID => $sValue)
														{
															echo '<div class="reply-box-item" id="r2reply'.$sValue["replyID"].'">
																<div class="reply-box-name">'.$user->getDisplayNameFromArray($sValue).'</div>
																<div class="reply-box-usercontent"">'.$sValue["subreplyCreatedDateTime"].'</div>
																<div class="reply-box-usercontent">'.$sValue["replyContent"].'</div>
															</div>';
														}
													}												
												?>
												</div>
											</div>
										</div>
							<?php			
									}
								}
							?>
							
						</div>
						<div style="width: 40px; float: left;">
							<div class="timeline-vertical"></div>
						</div>
						<!-- <div style="width: 570px; float: left; padding-left: 20px; min-height: 1px"> -->
						<div class="no-debate">
							<div class="disagree-box " <?php echo (@$userVoted["voteType"] == "disagree")? "style='display:block'":"";?>>
								<div class="debate-box-disagree-dot"></div>
								<div class="debate-box-disagree-arrow"></div>
								<form class="debate-reply2" name="debate-reply2"  data-newsID="<?php echo $newsID ?>">
									<div class="debate-reply-ask">Why No? Tell us more.</div>
									<!--div class="">
										<label>Your Statement</label>
										<input type="text" name="replyStatement" maxlength="60">
									</div-->
									<div class="">
										<label>Write Your Opinions</label>
										<textarea class="disagree-opinion" name="replyDesc"></textarea>
									</div>
									<div class="row ">
										<div class="six columns">
											<div class="agree-charcount">Character limit (<span class="disagree-opinion-count"></span>/2000)</div>
										</div>
										<div class="six columns text-right">
											<input type="submit" name="send" value="SUBMIT" class="primarybtn2">
										</div>
										
									</div>
									<input type="hidden" name="newsID" value="<?php echo $newsID?>">
									
									<input type="hidden" name="replyType" value="disagree">
									<input type="hidden" name="userID" value="<?php echo @$_SESSION["userID"]?>">
									<input type="hidden" name="displayName" value="<?php echo @$_SESSION["displayName"]?>">
									
								</form>
								<div class="debate-notification">
									<div class="debate-notification-title">Thank you for submitting  your opinion.</div>
									<a href="javascript:;" class="primarybtn2 writeagain">Write Again</a>
								</div>
							</div>
	
	
							<?php
								$disagreeReplyRstArr = $news->DisplayReplyDetails(1,10000,$newsID,"disagree");
	
								if(is_array(@$disagreeReplyRstArr["List"]) && count(@$disagreeReplyRstArr["List"]) > 0)
								{
									foreach($disagreeReplyRstArr["List"] as $id =>$disagreeArr)
									{
										$disagreeLikes = $news->ReplyLikes($disagreeArr["replyID"]);
										$disagreeLikes = (!$disagreeLikes)? array():$disagreeLikes["usercontent"];
							?>
										<div class="debate-box debates<?php echo $disagreeArr['replyID']?>" id="debate-box-<?php echo $disagreeArr["replyID"]?>">
											<div class="debate-box-disagree-dot"></div>
											<div class="debate-box-disagree-arrow"></div>
											<div class="debate-box-userdetails clearfix">
												<div class="debate-box-userdetails-avatar">
													<?php	
														if($disagreeArr["userProfilePicture"] == "" || $disagreeArr["userProfilePicture"] == "undefined")
														{
															echo '<img src="img/replyavatar.jpg">';
														}
														else
														{
															echo '<img src="uploads/profile/thumbnail/'.$disagreeArr["userProfilePicture"].'">';
														}
													?>
												</div>
												<div class="debate-box-userdetails-name">
													<?php
														echo $user->getDisplayNameFromArray($disagreeArr);
													?>			
												</div>
												<!-- <div> -->
												<a href="javascript:;" title="I like!" class="debate-box-userdetails-likes <?php 
														if (@$_SESSION["userID"] == 0 && $user->getAnonymousID() == 0)
															echo "disabled";
														?>" data-replyID="<?php echo $disagreeArr["replyID"]?>" data-newsID="<?php echo $newsID?>">
													<i class="icomo-love <?php 
														if (in_array(@$_SESSION["userID"],$disagreeLikes) ||
																in_array($user->getAnonymousID(),$disagreeLikes))
															echo 'selected';
														?>"></i><span><?php echo count($disagreeLikes)?></span>
												</a>
												<a href="javascript:;" class="debate-box-userdetails-comment" title="View Reply Details" data-id="<?php echo $disagreeArr["replyID"]?>">
													<i class="icomo-reply"></i><?php echo count(@$disagreeArr["subreply"])?>
												</a>											
												<a href="javascript:;" class="debate-box-userdetails-opinion" data-id="<?php echo $disagreeArr["replyID"]?>" title="View Opinion Details"><i class="icomo-recommend"></i>Details</a>
												
												<!-- <a href="javascript:;" class="debate-box-userdetails-report">
													<i class="icon-attention"></i>
													Report Post
												</a> -->
												<!-- </div> -->
											</div>										
											<!--div class="debate-box-usercontent"><strong><?php echo $disagreeArr["replyStatement"]?></strong></div-->
											<div class="debate-box-usercontent"><?php echo $disagreeArr["replyCreatedDateTime"]?></div>										
											<div class="debate-box-usercontent-opinion debate-usercontent-opinion<?php echo $disagreeArr['replyID']?>"><?php echo $disagreeArr["replyContent"]?></div>										
											
	
											<div class="reply-box reply<?php echo $disagreeArr["replyID"]?> clearfix">
												<form class="reply-form text-right">
													<?php 
	
														if(@$_SESSION["userID"] == 0 && $user->getAnonymousID() == 0)
														{
													?>
															<div class="replybox-prohibited">
																<div class="replybox-prohibited-title">Oops. You are not logged in.</div>
																<div class="replybox-prohibited-content">
																	<a href="javascript:;" class="switchpopup" data-to="login-popup">Sign in</a> to Newslogue or <a href="javascript:;" class="switchpopup" data-to="registration-popup">create an account</a> to comment.
																</div>
															</div>
															
													<?php
														}
														else
														{
													?>
															<textarea name="replyDesc" placeholder="Write your reply..." class="reply-textarea"></textarea>
															<input type="hidden" name="newsID" value="<?php echo $newsID?>">
															<input type="hidden" name="replyID" value="<?php echo $disagreeArr["replyID"]?>">
															<input type="hidden" name="userID" value="<?php echo $_SESSION["userID"]?>">
															<input type="hidden" name="newsID" value="<?php echo $newsID?>">
															<input type="hidden" name="replyType" value="disagree">
															<input type="hidden" name="send" value="SUBMIT">
															<input type="hidden" name="displayName" value="<?php echo @$_SESSION["displayName"]?>">
															<input type="submit" class="primarybtn" value="Submit">
													<?php
	
														}
													?>
													
										            
												</form>
												<div class="reply-listing">
												<?php
													if(is_array(@$disagreeArr["subreply"]))
													{
														foreach($disagreeArr["subreply"] as $sID => $sValue)
														{
															echo '<div class="reply-box-item" id="r2reply'.$sValue["replyID"].'">
																<div class="reply-box-name">'.$user->getDisplayNameFromArray($sValue).'</div>
																<div class="reply-box-usercontent"">'.$sValue["subreplyCreatedDateTime"].'</div>
																<div class="reply-box-usercontent">'.$sValue["replyContent"].'</div>
															</div>';
														}
													}
												?>
												</div>
											</div>
										</div>
							<?php			
									}
								}
							?>
						</div>									
					</div>
				</div>
				
			</div>
		</div>
		
	</section>