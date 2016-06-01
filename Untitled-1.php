
<p><button id="modal_open">Open Email Form</button></p>

<div id="modal_wrapper">
<div id="modal_window">

<div style="text-align: right;"><a id="modal_close" href="#">close <b>X</b></a></div>

<p>Complete the form below to send an email:</p>

<form id="modal_feedback" method="POST" action="#" accept-charset="UTF-8">
<p><label>Your Name<strong>*</strong><br>
<input type="text" autofocus required size="48" name="name" value=""></label></p>
<p><label>Email Address<strong>*</strong><br>
<input type="email" required title="Please enter a valid email address" size="48" name="email" value=""></label></p>
<p><label>Subject<br>
<input type="text" size="48" name="subject" value=""></label></p>
<p><label>Enquiry<strong>*</strong><br>
<textarea required name="message" cols="48" rows="8"></textarea></label></p>
<p><input type="submit" name="feedbackForm" value="Send Message"></p>
</form>

</div> <!-- #modal_window -->
</div> <!-- #modal_wrapper -->

<script type="text/javascript">

  // Original JavaScript code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.

  var modal_init = function() {

    var modalWrapper = document.getElementById("modal_wrapper");
    var modalWindow  = document.getElementById("modal_window");

    var openModal = function(e)
    {
      modalWrapper.className = "overlay";
      modalWindow.style.marginTop = (-modalWindow.offsetHeight)/2 + "px";
      modalWindow.style.marginLeft = (-modalWindow.offsetWidth)/2 + "px";
      e.preventDefault ? e.preventDefault() : e.returnValue = false;
    };

    var closeModal = function(e)
    {
      modalWrapper.className = "";
      e.preventDefault ? e.preventDefault() : e.returnValue = false;
    };

    var clickHandler = function(e) {
      if(!e.target) e.target = e.srcElement;
      if(e.target.tagName == "DIV") {
        if(e.target.id != "modal_window") closeModal(e);
      }
    };

    var keyHandler = function(e) {
      if(e.keyCode == 27) closeModal(e);
    };

    if(document.addEventListener) {
      document.getElementById("modal_open").addEventListener("click", openModal, false);
      document.getElementById("modal_close").addEventListener("click", closeModal, false);
      document.addEventListener("click", clickHandler, false);
      document.addEventListener("keydown", keyHandler, false);
    } else {
      document.getElementById("modal_open").attachEvent("onclick", openModal);
      document.getElementById("modal_close").attachEvent("onclick", closeModal);
      document.attachEvent("onclick", clickHandler);
      document.attachEvent("onkeydown", keyHandler);
    }

  };

</script>

