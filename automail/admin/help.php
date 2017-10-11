<?php
//nuevoMailer v.3.70
//Copyright 2014 Panagiotis Chourmouziadis
//http://www.nuevomailer.com
?>

<html>
<head>
	<title>Online Help - nuevoMailer</title>
	<link href="./includes/help.css" rel=stylesheet type=text/css>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<script type="text/javascript" language="javascript">
	function $Npro(field){var element =  document.getElementById(field);return element;return false;}
	function $VNpro(field){var element =  document.getElementById(field).value;return element;return false;}
	function goToItem(){ 
		if ($VNpro("goToItem")!=0)		{
			//alert($VNpro("goToItem"));
			document.location.href='help.php#'+$VNpro("goToItem");
		}
	}
</script>
<div style="position:fixed;height:70px;top:0px;BACKGROUND: #555555; WIDTH: 100%;z-index: 1000;padding:10px">
	<div style="VERTICAL-ALIGN: top" align="left">
		<span style="FONT-WEIGHT: bold; FONT-SIZE: 18pt; COLOR: #ffffff; FONT-FAMILY: Tahoma">Online help</span>
		&nbsp;&nbsp;&nbsp;<a href=# onclick = "window.close();return false;"><img src="./images/close.gif" border=0 style="float:right;margin-right:20px"></a>
	</div>
	<div style="VERTICAL-ALIGN: top">
					<span style="FONT-WEIGHT: normal; FONT-SIZE: 12pt; COLOR: #ffffff; FONT-FAMILY: Verdana">Select a subject:</span>
					<select name="goToItem" id="goToItem" onchange="goToItem();">
					<option value=0>Please select a help topic</option>
					<option value="start">How to start</option>
	                <option value="lcl">Localization for non-English speaking users</option>
					<option value="subs">Subscribers</option>
					<option value="nsls">Newsletters</option>
					<option value="mlists">Mailing lists</option>
	 				<option value="doi">Double opt-in verification and Subscriber messages</option>
					<option value="unsublink">Un-subscribe link (opt-out)</option>
	                <option value="urlsend">Send a web page using the URL</option>
	                <option value="smartlinks">Smart links</option>
	                <option value="campaigns">Campaigns and reports</option>
	                <option value="filters">Filters</option>
	                <option value="tips">Sending tips</option>
					</select>
	</div>
</div>	
			
  <div class="hwrapper">


        <span id="start" class="help_anchor"></span><span class="helpHeader">How to start</span>
        <div >
          <ol>
            <li>
              <div>Go to <i>Menu>Tools>Administrator accounts</i> and change your administrator account data. </div></li>
            <li>
              <div>Go to <i>Menu>Tools>Configuration &amp; settings</i> and update your settings. </div></li>
            <li>
              <div>In the same page under <i>General settings</i> select the <i>Email sending method</i>. If you use SMTP open the SMTP servers window and add an SMTP server. This window is also accessible under <i>Menu>Tools</i>.</div></li>
            <li>
              <div>Verify that your <i>email sending method</i> works. Create a new html newsletter and send a single test email directly from the editor. If you see an error or do not receive the email after a few minutes, adjust again at your sending method (or SMTP server).</div></li>
            <li>
              <div>After you have a working sending method create a test list with 1-2 emails of yours and send a campaign to it. </div></li>
            <li>
              <div>Verify that views &amp; clicks are captured. If not then check your <i>Views and clicks tracking options</i> in your configuration settings. Double check also the "<i>... location where nuevoMailer is installed...</i>".</div></li>
            <li>
              <div>Go to your newsletter editor and try to upload an image or an attachment. If you see the message  "Upload failed" then you may want to give "write permissions" to these folders: assets, attachments, data_files.
			  <div>To verify your permissions, test with small size files first. Larger files may be blocked due to your php.ini settings.</div></div></li>
         </ol>
          </div>
     
     <div  align="right"><a href="#top"><img src="./images/top2.gif" border="0"/></a></div>
<!--localization-->
      <span id="lcl" class="help_anchor"></span><span class="helpHeader">Localization for non-English speaking users</span>
      
        <div >
              <p><span style="FONT-WEIGHT: bold"></span>
               <div align="justify">
               <ul>
                   <li>
				   	<div>All users are recommended to use utf8 character encoding which is a safe choice for all languages.</div>
				   	<div>We provide scripts to create the tables both in utf8 and latin1 encodings.</div>
					<div>The script you used to create your tables defines also the <i>Global character set</i> you must use in your configuration settings.</div>
					<div>So use an equivalent encoding: both in utf8 or latin1 with iso-8859-1/3.</div>
				   </li>
                  <li>
				  	<div>Define the character set at newsletter level (with the same logic as above).</div>
				  </li>
                  <li>
				  	<div>Define the character set for the landing pages (welcome, goodbye etc) by adding this line in the html code:</div>
                    <div><?php echo '< meta http-equiv="Content-Type" content="text/html; charset=iso-8858-1">';?> OR </div>
					<div><?php echo '< meta http-equiv="Content-Type" content="text/html; charset=utf-8">';?></div>
                  </li>
             </ul>
             </div>
               </p>
          </div>
     
     <div  align="right"><a href="#top"><img src="./images/top2.gif" border="0"/></a></div>
<!--Subscribers-->
      <span id="subs" class="help_anchor"></span><span class="helpHeader">Subscribers</span>
      
        <div >
		   <div>The subscriber is the most important entity of your nuevoMailer.</div>
               <div>
			<ul>
                     <li>
                           A subscriber is assigned (subscribed) to one or more lists.
			   	    </li>
                       <li>
                           A <span style="FONT-WEIGHT: bold">non-assigned subscriber </span>is one who is not subscribed to any lists. It is a good organizing practice to use lists and assign your subscribers to lists. If however a subscriber has opted-out from all lists but he did not do a global-opt out then he will remain in your system as non-assigned.
				   </li>
                      <li>
				       A subscriber can have a <span style="FONT-WEIGHT: bold">confirmed or a non-confirmed</span> status depending on how you use the double opt-in verification system.
		               You can send confirmation reminders or manually confirm all subscribers with a single click.
                      </li>
			   </ul>
   		   </div>
              <p><span style="FONT-WEIGHT: bold">Subscriber passwords</span>
               <div align="justify">A password is used in the double opt-in confirmation process and is also required when a subscriber attempts to log in to his personal area to update his subscriptions. Passwords are generated automatically when you add/import subscribers. </div>
               </p>
          </div>
     
     <div  align="right"><a href="#top"><img src="./images/top2.gif" border="0"/></a></div>

<!--Newsletters-->
     <span id="nsls" class="help_anchor"></span><span class="helpHeader">Newsletters</span>
      
        <div >
           <div>The newsletter is the other most important entity in your system. It is what you send to your subscribers.</div>
           <p><div align="justify"><span style="FONT-WEIGHT: bold">Public and hidden newsletters</span></div>
           <div>When a newsletter is set to public (and has been sent) then it appears in the newsletter archive.</div>
           <div>This is the link to the archive: http://www.yourdomain.com/nuevoMailer/subscriber/newsletterArchive.php</div>
           </p>
           <p><div align="justify"><span style="FONT-WEIGHT: bold">Personalization</span></div>
           <div align="justify">With nuevoMailer you can extensively personalize your newsletters.<br />
               In <strong>HTML</strong> newsletters use the "Tag" button of the HTML editor.<br />
               In <strong>Text</strong> newsletters you must manually type some special tags like #subname#. You will find a complete list of all these tags in the page where you write a text newsletter.
           </div>
           <div align="justify"><span style="FONT-WEIGHT: bold">Personalized subject lines: </span>If you type #subname# and #sublastname# in the subject of your newsletter they will be automatically replaced by the subscriber's name and last name.<br /></div>
           </p>

           <p><div align="justify"><span style="FONT-WEIGHT: bold">Attachments</span></div>
           <div align="justify">
               You can have many attachments within a newsletter. Separate them with a comma (,). No spaces required. All your attachments must be placed in the attachments folder. To see and browse your attachments there is an integrated file manager. You can use it directly from the page where you design your newsletter. Click on the icon next to the attachments input field and a new window will open. This window is an online file manager that actually shows you the attachments you have uploaded. Check the box of the file that you want to attach and the name is automatically entered in the input area.
               All files are supported and can be send as attachments. Docs, PDFs, images etc...
               <br><strong>Note:</strong> attachments decrease the speed of mailing. Therefore if you want to send a large attachment it is better to upload it somewhere and insert a link to it in the newsletter.
           </div>
           </p>
           <p><div align="justify"><span style="FONT-WEIGHT: bold">Inline images (embedded)</span></div>
           <div align="justify">
               For security reasons modern email clients block images from displaying in a newsletter unless you have already defined the sender as verified (or trusted).
               <br />So the subscriber in order to see the images has to explicitly allow image downloading in the newsletter.
               <br />nuevoMailer gives you the option to send your images as inline (or embedded). You can activate it in your settings page. In this way you increase the chances of having the images displayed automatically.
               <br>However, many modern email clients will still block inline images from appearing.
               <br><strong>Note:</strong> using inline images decreases the speed of mailing.
           </div>
           </p>
           <p><div align="justify"><span style="FONT-WEIGHT: bold">Importing a newsletter by using HTML code</span></div>
               <div align="justify">When you have prepared your newsletter in another program (Dreamweaver, FrontPage, Expression Web etc.) you can copy-paste your html code in the nuevoMailer editor and save it as a newsletter.</div>Click on the HTML editor's button
               <img src="./images/htmlcode.gif" /> that says "View/Edit Source". You will see the complete HTML code between the &lt;HTML>, &lt;/HTML> tags. You can paste directly your own code and you can also save code in the &lt;head> section. In this way you can add styles and css in your newsletter.
           </p>
           <p><div align="justify"><span style="FONT-WEIGHT: bold">Uploading images</span></div>
               <div align="justify">The HTML editor of nuevoMailer has an integrated utility to upload and insert images into your newsletters. By default images are uploaded into the assets folder.
               <br />Click on this button to open it: <img height="25" alt="" src="./images/assetbutton.gif" width="29" border="0" /></div>
               <div align="justify"><span style="FONT-WEIGHT: bold">Using Inline images?</span> Make sure that the images are located in your assets folder.</div>
           </p>
      </div>
     
     <div  align="right"><a href="#top"><img src="./images/top2.gif" border="0"/></a></div>

<!--Mailing lists-->
     <span id="mlists" class="help_anchor"></span><span class="helpHeader">Mailing lists</span>
     
        <div >
          <p align="justify">Consider a mailing list as a grouping of subscribers that have some common characteristics or interests. A mailing list is also a good and necessary way for the management of your subscribers. </p>
          <ul>
              <li>
              <div align="justify">A subscriber can belong to one or more mailing lists.</div></li>
           <li>
              <div align="justify">You may create as many mailing lists as you like. </div></li>
           <li>
              <div align="justify">For every mailing list you may define a name and a short description (that your subscribers can see if you give them this option).</div></li>
           <li>
              <div align="justify">A list can be <span style="FONT-WEIGHT: bold">public</span> or <span style="FONT-WEIGHT: bold">hidden </span>. When a mailing list is hidden the subscriber will not see it when he logs in his personal area to update his account. </div></li>
         </ul>
           <p><div align="justify"><span style="FONT-WEIGHT: bold">Different sign-up forms in several pages</span></div>
           <div align="justify">
               <br />Create a new (hidden) list.
               <br />Create a "sign up form" and select this list in the option "Use a hidden list".
               <br />If you use the generated form then the subscriber will not have to select any lists. He is silently assigned to this list.
               <br />Copy-paste the form to the web page that you want.
               <br />Create several hidden lists for the pages that you want and repeat the above steps.
               <br />By using a specific hidden list for every different web page you know where your subscriber came from.
               <br />This approach can vary depending on your needs.
           </div>
           </p>
           <p><div align="justify"><span style="FONT-WEIGHT: bold">Delete vs Dropping a list</span></div>
           <div align="justify">
               <br />Deleting a list: you delete only the list. You do not delete subscribers. If some subscribers are assigned to other lists this status will be maintained.
               <br />Dropping a list: you delete both the list and the subscribers. The subscribers that were assigned to this list will be removed from your system whether or not they are assigned to other lists.
           </div>
           </p>

         </div>
     
      <div  align="right"><a href="#top"><img src="./images/top2.gif" border="0"/></a></div>

<!--Double opt-in verification and Subscriber messages***-->
     <span id="doi" class="help_anchor"></span><span class="helpHeader">Double opt-in verification and Subscriber messages</span>
      
        <div >
          <div align="justify">You may enable/disable the double opt-in verification system in <i>Menu>Newsletters>Subscriber messages>Welcome &amp; goodbye emails &amp; pages.</i></div>
          <div align="justify">You can define all the screen messages (or landing pages) and emails both in HTML and in text format in he same page.
           In addition you have utilities to send confirmation reminders, to manually confirm subscribers and to delete un-confirmed subscribers older than a given number of dates.</span></div>
          <p align="justify"><span style="FONT-WEIGHT: bold">Double opt-in active</span></p>
          <ol>
            <li>
              <div align="justify">The subscriber is inserted with the status of "un-confirmed".</div></li>
            <li>
              <div align="justify">He automatically goes to the "Confirmation-required" page (or URL). </div></li>
            <li>
              <div align="justify">The "Confirmation-required" email is sent asking to confirm the subscription.</div></li>
            <li>
              <div align="justify">He clicks on the confirmation link in the "Confirmation-required" email, his status changes to "confirmed" and a new window opens with the "Welcome" page (or he is redirected to the URL you want).</div></li>
            <li>
              <div align="justify">The "Welcome" email is sent (if activated).</div></li>
         </ol>
          <div align="justify"><span style="FONT-WEIGHT: bold">Double opt-in NOT active</span></div>
          <ol>
            <li>
              <div align="justify">The subscriber is inserted with the status of "confirmed". </div></li>
            <li>
              <div align="justify">He sees the "Welcome" page.</div></li>
            <li>
              <div align="justify">The "Welcome" email is sent.</div></li>
         </ol>
          <p align="justify"><strong>Customizable, built-in landing pages and emails</strong><br />
               nuevoMailer allows you to set up complete web pages that your subscribers will see after a specific action. You can also use your own pages by defining redirect URLs.<br />
               You can disable the "welcome" and "goodbye" emails that are sent. But you cannot disable the "confirmation-required" email since it is required in order to confirm a subscription (when double opt-in is active)<br />
               <strong>Welcome page</strong><br />
               Subscribers will see this page after subscribing (when double opt-in not active) or after they confirm a subscription (when double opt-in is active).<br />
               <strong>Confirmation-required page</strong><br />
               Subscribers will see this page after they confirm a subscription (when double opt-in is active).<br />
               <strong>Goodbye page</strong><br />
               Subscribers will see this page when they click an opt-out link in a newsletter. The "goodbye" email is sent.<br />
               <strong>Already-in page</strong><br />
               This page is presented when an existing subscriber attempts to subscribe again. No email is sent in this case. In addition and instead of showing this page you may redirect the subscriber to update his account or to any URL that you want.
               </p>
          <p align="justify"><strong>Smart tags</strong><br />You may also use smart tags in all these messages. For the html messages you can use the "<i>Subscriber tags</i>" menu button to insert them. For text messages you will find a detailed list in the relevant page.</p>
          <p align="justify"><span style="FONT-WEIGHT: bold">Chosen lists</span><br />
           	By using this tag you can show the subscriber the lists he subscribed. <br />
           	In the html messages you can use the "<i>Subscriber tags</i>" menu button. In text messages you simply type: #listlistingT#.</p>
          </div>
     
    <div  align="right"><a href="#top"><img src="./images/top2.gif" border="0"/></a></div>

<!--Un-subscribe link (opt-out link)-->
     <span id="unsublink" class="help_anchor"></span><span class="helpHeader">Un-subscribe link (opt-out link)</span>
      
        <div >
          <div align="justify"><span style="FONT-WEIGHT: bold">For HTML newsletters</span></div>
          <div align="justify"><span style="FONT-WEIGHT: bold"></span>When you edit an HTML newsletter click on this icon
           <img src="./images/optOutSample.gif" alt="" width="85" height="26"> . Select any word, phrase or image and choose the required action using the menu in the pop-up window. The available actions are:</div>
          <ol>
            <li>
              <div align="justify"><span style="FONT-WEIGHT: bold">Global opt-out: </span>the subscriber will be directly deleted from all the lists he is subscribed and from the subscribers table. In other words, he is permanently deleted. He is also added in the opt-outs table.</div>
            </li>
            <li>
              <div align="justify"><span style="FONT-WEIGHT: bold">List opt-out:</span> the subscriber will be deleted only from the specific list(s) where he belongs and the newsletter was sent to. He is not deleted from the subscribers table and he keeps the rest of his subscriptions (in other lists), if he has any.
              He is also added in the opt-outs table for each and every list.</div>
              <div align="justify"><strong>Attention:</strong> When you use a list opt-out in a newsletter/campaign that was sent to all subscribers or all lists then it works as a global opt-out and results in a permanent deletion.</div>
            </li>
            <li>
              <div align="justify"><span style="FONT-WEIGHT: bold">Update settings link:</span> this link will directly log-in the subscriber into a password protected page where he can see all available lists, cancel and/or start new subscriptions update his profile and email.</div>
            </li>
         </ol>
          <p align="justify"><span style="FONT-WEIGHT: bold">For Text newsletters</span></p>
          <ol>
            <li>
              <div align="justify"><span style="FONT-WEIGHT: bold">List opt-out:</span> simply type <span style="COLOR: rgb(0,0,205)">optoutlink1</span></div></li>
            <li>
              <div align="justify"><span style="FONT-WEIGHT: bold">Global opt-out: </span>type <span style="COLOR: rgb(0,0,205)">optoutlink2</span></div></li>
            <li>
              <div align="justify"><span style="FONT-WEIGHT: bold">Update settings link:</span> type <span style="COLOR: rgb(0,0,205)">optoutlink3</span></div></li>
            <li>
              <div align="justify">The links will be automatically formatted and personalized for each subscriber during the sending. </div></li>
         </ol></div>
     
     <div  align="right"><a href="#top"><img src="./images/top2.gif" border="0"/></a></div>

<!--Send</a> a web page using the URL-->
     <span id="urlsend" class="help_anchor"></span><span class="helpHeader">Send a web page using the URL</span>
      
        <div >
          <div>You can send a full web page that is located on a web server by entering the url of the page. You must also enter a subject for this email. It is like sending an html newsletter. If you have used smart tags (like #subname# etc.) then the page will be personalized for each subscriber.</div>
          <div>With this method views and clicks tracking statistics work in exactly the same way when you send an existing html newsletter. </div>
           <div><b>Note:</b> When sending a web page as a newsletter the link "Click to read the newsletter in your browser" is not applicable.</div></div>
     
     <div  align="right"><a href="#top"><img src="./images/top2.gif" border="0"/></a></div>

<!--Smart links-->
     <span id="smartlinks" class="help_anchor"></span><span class="helpHeader">Smart links</span>
      
        <div >
           <div>In the html newsletter editor you have a Smart links button: <img height="20" alt="" src="./images/smartLinks.gif" width="85" border="0" /></div>
           <div>It enables you to insert some special links in your newsletter such as:</div>
           <p align="justify"><span style="FONT-WEIGHT: bold">A link to your "Privacy" page</span> (Html/Text)
               <div>You can edit your "Privacy" page by going to<i> Menu>Newsletters>Privacy page</i>.<br />This is a dynamically generated page that is located in the subscriber folder of your nuevoMailer.</div>
               <div>In text newsletters you can simply type the url to your privacy page (http://www.yourdomain.com/nuevoMailer/subscriber/privacy.php). </div>
           </p>
           <!--Inserting a link to read the newsletter in a browser-->
           <p align="justify"><span style="FONT-WEIGHT: bold">A link to read the newsletter in a browser</span> (Html / multipart)
          <div align="justify">This is a dynamically generated page located in the subscriber folder of your nuevoMailer (newsletter.php).
          It is strongly recommended to start a newsletter with this link at the very top:
          <ul>
            <li>
              <div>Email clients may block images from appearing. When you read the newsletter in a browser images will appear.</div></li>
            <li>
              <div>It feels better to read a newsletter in a big window. The newsletter looks nicer.</div></li>
            <li>
              <div>It is safer in cases where the subscriber's email client is old or problematic (cannot render html, css etc).</div></li>
           <li>
              <div>The view of this page is captured. All clicks from this page are captured. So your campaign statistics are updated.</div></li>
            <li>
              <div><b>Tip</b>: when sending <b>multipart</b> you can use #webpagelink# in the text newsletter to click and load the html newsletter in the browser. You can see an example in the User's guide.</div></li>
         </ul>
         </div>
        </p>

       <!--Forward to a friend-->
           <p align="justify"><span style="FONT-WEIGHT: bold">Forward to your friends</span> (Html/Text)
               <div>When your subscriber clicks this link he goes to an existing page of nuevoMailer where he can forward this newsletter to 5 other email accounts.
               The forwards are captured and you can see them in your campaign summary report.</div>
               <div>For text newsletters use:&nbsp;#friendForwardLink#</div>
         </p>
     <!--Inserting a link to your Newsletter archive-->
          <p align="justify"><span style="FONT-WEIGHT: bold">Newsletter archive</span>  (Html/Text)
            <div>The Newsletter archive is a dynamically generated page where all newsletters that have been sent and are set to "public" appear.</div>
            <div>This is the link to the archive: http://www.yourdomain.com/nuevoMailer/subscriber/newsletterArchive.php</div>
           </p>
     <!--Inserting a rating link-->
          <p align="justify"><span style="FONT-WEIGHT: bold">Rating links</span> (Html)
            <div>You can use these links as their name implies (for rating the newsletter) but also as a voting system. You can change the wording of the links as you like.</div>
           </p>
     </div>
     
     <div  align="right"><a href="#top"><img src="./images/top2.gif" border="0"/></a></div>

<!--Campaigns-->
     <span id="campaigns" class="help_anchor"></span><span class="helpHeader">Campaigns and reports</span>
      
        <div >
           <p><div align="justify"><span style="FONT-WEIGHT: bold">Creating a new campaign</span></div>
              <div align="justify">You can create a new campaign under "<i>Menu>Campaigns>New campaign</i>".</div>
            </p>
           <p><div align="justify"><span style="FONT-WEIGHT: bold">Starting the mailing</span></div>
              <div align="justify">To start the mailing go "<i>Menu>Campaigns>View and start campaigns</i>". Click the "<i>Start mailing</i>" button.
               A new window will open and the sending will start. This window must stay open. Alternatively when using the campaign scheduler you can create a task for this campaign and send it via a cron command. </div>
            </p>
           <p><div align="justify"><span style="FONT-WEIGHT: bold">Pause and continue a mailing</span></div>
              <div align="justify">If for some reason you want to stop your mailing then simply close the browser window that does the sending.
               Then at anytime later you can go to the mailing activity page and click on the "<i>Continue mailing</i>" button. The sending will continue from the subscriber it stopped.</div>
           </p>
           <p><div align="justify"><span style="FONT-WEIGHT: bold">How views tracking works</span></div>
               <div align="justify">A "newsletter view" can only be captured when the subscriber is connected to the Internet at the time she opens her email and also allows picture downloading.</div>
            </p>
           <!--Batch sending-->
           <p><div align="justify"><span style="FONT-WEIGHT: bold">Batch sending</span></div>
          <div align="justify">This is a great feature to use if your host restricts the amounts of emails you can send per time period but also when you do not want to overload your server.</div>
          <ol>
            <li>
              <div align="justify">You can activate it in your configuration settings. You may define the number of emails to be sent (=batch size) and the time break (=batch interval) between two batches (mailings). </div></li>
            <li>
              <div align="justify">nuevoMailer will send the first batch, pause, then send the next one and so on until all emails are sent. </div></li>
            <li>
              <div align="justify">Batch sending does not consume any server resources when pausing. The action happens in the client (browser). </div></li>
            <li>
              <div align="justify">Even if you do not face any restrictions by your host, batch sending can be used to <span style="FONT-WEIGHT: bold">ease the load on the server</span> especially when sending to large lists. </div></li>
         </ol>
         </p>
       </div>
     
     <div  align="right"><a href="#top"><img src="./images/top2.gif" border="0"/></a></div>

<!--Using filters-->
     <span id="filters" class="help_anchor"></span><span class="helpHeader">Filters</span>
      
        <div >
           <div align="justify">Filters are extra criteria that you can apply to a campaign and will result in excluding or including subscribers on the basis of demographic parameters, tracking behavior, subscription dates and more.</div>
            <div>Here are some examples and applications</div>
          <p>
               <div><strong>Campaign follow-up filters</strong></div>
               <div>Such filters are created automatically when you create follow-up campaigns.</div>
          </p>
          <p>
           <div><strong>Demographic filters</strong>
           <div align="justify"><i>Menu>Filters>Create new>Based on subscriber properties/data</i></div>
           <div align="justify">In this page you can:</div>
            <ul>
              <li>
                <div align="justify">perform advanced searches on subscribers using any of their available fields</div></li>
              <li>
                <div align="justify">preview and export the results to spreadsheets like Excel, CALC.</div></li>
              <li>
                <div align="justify">create filtered lists (sub-lists) based on the search results</div></li>
              <li>
                <div align="justify">create filters that you can save, refine and use in your mailings</div></li>
           </ul>
           </div>
          </p>
          <p>
               <div><strong>Empty, blank filter</strong>
              <div><i>Menu>Filters>Create new>Create a blank filter</i></div>
              <div align="justify"><span style="COLOR: rgb(0,0,205)"></span>You simply create an empty filter and then you can add your own sql code or copy code from another filter.</div>
              </div>
          </p>
          <p align="justify">
               <div>
                   <strong>Birthday filter</strong>
                   <div><i>Menu>Filters>Create new>Create a birthday filter</i></div>
                   <div align="justify">With nuevoMailer you can capture (or import) your subscribers birthdays. You can create a birthday filter, a "Happy birthday" newsletter and make campaigns that use these. 
				   <br>By applying the birthday filter the mailing will go only to the matching subscribers.</div>
                   <div align="justify">The birthday filter is always valid (at all times for all campaigns). There is no need to change it every day.</div>
                   <div align="justify">When using the campaign scheduler you can have your birthday campaigns sent automatically every day.</div>
               </div>
           </p>
          <p align="justify">
               <div>
                   <strong>Filter by subscription date</strong>
                   <div><i>Menu>Filters>Create new>Follow-up by subscription date</i></div>
                   <div align="justify">With this feature you can implement the classic scenario of following up with your subscribers on specific days after they subscribed by sending a different newsletter on every day.</div>
               </div>
          </p>
          <p align="justify">
               <div>
                   <strong>Refining a filter</strong>
                   <div align="justify">If you are familiar with sql syntax you can change the sql code and refine an existing filter. You can also merge filters or create your own.</div>
               </div>
          </p>
          <p>
               <div align="justify">
                   <strong>Testing a filter</strong>
                   <div align="justify">To test a filter open a new window go to the page where you create a new campaign and click the "<strong>Count</strong>" button. Click again with/without selecting a filter so you can see the difference.</div>
               </div>
          </p>

          <div align="justify"><span style="FONT-WEIGHT: bold">Examples &amp; tips</span></div>
          <div align="justify">Using filters you can find and create a sub-list or a mailing filter or export subscribers who: </div>
          <ul>
            <li>
              <div align="justify">come from a specific country and subscribed after a certain date</div></li>
            <li>
              <div align="justify">have a certain zip code</div></li>
            <li>
              <div align="justify">have not updated their account since a given date</div></li>
            <li>
              <div align="justify">come from the same company</div></li>
            <li>
              <div align="justify">and much more...</div></li>
         </ul>

          <div><span style="FONT-WEIGHT: bold">Create a sub-list or a mailing filter?</span></div>
          <div><span style="FONT-WEIGHT: bold"></span>If you plan to make a new list that you will use in the future then it is better to create a sub-list with the search results.</div>
          <div align="justify">If you only want to do a few mailings to this subscriber group then it is perhaps better to create a mailing filter. The answer really lies in your subscriber management approach. </div></div>
     
    <div  align="right"><a href="#top"><img src="./images/top2.gif" border="0"/></a></div>

<!--Sending tips-->
     <span id="tips" class="help_anchor"></span><span class="helpHeader">Sending tips</span>
      
        <div >
           <div>Here are some tips from a technical point of view:</div>
           <ul>
            <li><div align="justify">Experiment with the 3 different sending methods (smtp, php-mail, sendMail) and see what gives you better results. Depending on your server environment some of them may not be available.</div></li>
            <li><div align="justify">Avoid large attachments and inline images as these decrease the speed of mailing and consume more bandwidth.</div></li>
            <li><div align="justify">Try to send off-peak hours. Consult with your Host if you are on shared hosting.</div></li>
            <li><div align="justify">Always use batch sending.</div></li>
         </ul>
       </div>
     
    <div  align="right"><a href="#top"><img src="./images/top2.gif" border="0"/></a></div>


			
 </div>						


</body>
</html>

