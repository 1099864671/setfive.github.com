<?php require_once "header.php"; ?>

	    <div class="row">
	        <div class="span12 logo-bg">
	            <div class="inner">
	                <img src="http://www.setfive.com/wp-content/themes/setfive_three/logo_website_no_consulting.png" />
	            </div>
            </div>
	    </div>

    <div class="shadow-box">
        <div class="row">
            <div class="span12">
                <div class="inner">
                    <h3>Sign Up To Play!</h3>
                    <p><strong>How it works: </strong> 
                        It's like "Deal or No Deal", 
                        you have to pick the joker to win a $1 and can walk away at any time or you can keep playing to win more while risking what you've won.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="span6 offset3">
            
                    <form action="form.php" id="signUp" class="form-horizontal sign-up-form">
                        <div class="control-group">
                          <label for="inputEmail" class="control-label">Email Address</label>
                          <div class="controls">
                            <input type="text" placeholder="Email" id="email" name="email">
                          </div>
                        </div>
                        <div class="control-group">
                          <label for="inputPassword" class="control-label">Name</label>
                          <div class="controls">
                            <input type="text" placeholder="Name" name="name">
                          </div>
                        </div>
                        <div class="control-group">
                          <label class="control-label">I am </label>
                          <div class="controls">
                              <select name="type">
                                  <option value="">Please select...</option>
                                  <option value="dev">A developer</option>
                                  <option value="other">Not a developer</option>
                              </select>
                          </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                            <button class="btn btn-large btn-primary" type="submit">Sign Up!</button>
                            </div>
                        </div>
                      </form>
            
            </div>
        </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function(){

        <?php 
            $data = json_decode( file_get_contents("contacts.json"), true );
            $emails = array();
            foreach( $data as $dt ){
                $emails[] = $dt["email"];
            }
        ?>
        var emails = <?php echo json_encode($emails);?>;
                
        $("#signUp").submit(function(){

            var hasError = false;
            $("input, select").each(function(){
                if( $.trim($(this).val()).length == 0 ){
                    alert("Come on! Fill everything in");
                    hasError = true;
                    return false;
                }
            });

            if( hasError ){
                return false;
            }

            var email = $("#email").val();
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if( re.test( email ) == false ){
                alert("Throw in a valid email :P");
                return false;
            }

            if( $.inArray(email, emails) > -1 ){
                alert("Sorry! You can only play once...");
                return false;
            }
            
        });
        
    });
    </script>
    
<?php require_once "footer.php"; ?>