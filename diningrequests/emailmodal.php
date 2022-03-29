<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="contactModalLabel">New message</h4>
      </div>
      <div class="modal-body">
        <form id="emailModal_form" onSubmit="return false;">
          <div class="form-group">
            <label for="your-email" class="control-label">Your Email:</label>
            <p class="text-danger email-alert"></p>
            <input type="text" class="form-control" id="your-email" name="email">
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Message:</label>
            <p class="text-danger message-alert"></p>
            <textarea class="form-control" id="message-text" name="message"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <a type="button" class="btn btn-primary contact-send-btn" onclick="emailMsg();">Send message</a>
        <button type="button" class="btn btn-default" id="emailModal_close_btn" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>