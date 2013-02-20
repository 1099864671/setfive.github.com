<?php require_once "header.php"; ?>

	    <div class="row">
	        <div class="span12 logo-bg">
	            <div class="inner">
	                <a href="index.php"><img src="http://www.setfive.com/wp-content/themes/setfive_three/logo_website_no_consulting.png" /></a>
	            </div>
            </div>
	    </div>

    <div class="shadow-box">
        <div class="row">
            <div class="span12">
                <div class="inner">
                    
                    <?php
$n = $_REQUEST["n"];
$win = rand(0, $n - 1);
$c = 0;
					?>
                    
                    <table class="table card-deck">
                        <tr>
                        <?php for ($i = 0; $i < $n; $i++) : ?>
                            <td>
                                <a data-provide="card" data-winner="<?php echo $i
			== $win ? "true" : "false" ?>" href="#"><img src="3427375948_430ac7e8da.png" /></a>
                            </td>
                        <?php
	$c++;
	if ($c == 4) {
		$c = 0;
		echo "</tr><tr>";
	}
endfor;
						?>
                        </tr>
                    </table>
                    
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="span12">
                
                <div class="inner centered win-image hide">
                    <img src="you-win-o.gif" />
                </div>
                
                <div class="inner centered lose-image hide">
                    <img src="you_lose_gif-392.gif" />
                </div>                
                
            </div>
        </div>
        
    </div>

<img src="joker_card_small.png" class="hide" />

<div id="keepPlaying" class="modal hide fade">
    <div class="modal-header">
    <h3>Do you want to keep playing?</h3>
    </div>
    <div class="modal-body">
    <p>Do you want to keep playing?</p>
    </div>
    <div class="modal-footer">
    <a href="#" class="btn btn-large" data-provide="no">No</a>
    <a href="#" class="btn btn-large btn-success" data-provide="yes">Yes</a>
    </div>
</div>
    
<script>
$(document).ready(function(){

    $("[data-provide='yes']").click(function(){
        var n = <?php echo $n + 1; ?>;
        window.location = "game.php?n=" + n;
        return false;
    });

    $("[data-provide='no']").click(function(){
        window.location = "index.php";
        return false;
    });
    
    $("[data-provide='card']").click(function(){
        var win = $(this).data("winner");

        if( win ){
            $(this).find("img").attr("src", "joker_card_small.png");
            $(".win-image").show();
            window.setTimeout(function(){
                $("#keepPlaying").modal({"backdrop": "static", "show": true});
            }, 2000);
        }else{
            $(this).find("img").attr("src", "ace_of_spades.png");
            $(".lose-image").show();
            window.setTimeout(function(){
                window.location = "index.php";
            }, 3000);
        }

        return false;
    });
        
});
</script>
    
<?php require_once "footer.php"; ?>