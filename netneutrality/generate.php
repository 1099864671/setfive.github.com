<?php

$handle = fopen("net_neutraility.csv", "r");
$i = 0;

$html = "";

while( ($parts = fgetcsv($handle)) ){
  $i += 1;
  if( $i == 1 ){
    continue;
  }
  
  $title = $parts[1];
  $img = $parts[4];
  $link = $parts[3];
  
  $date = DateTime::createFromFormat("n/j/Y", $parts[0]);
  $date = $date ? $date->format("F j Y")  : "";
  
  $lis = [];
  foreach( explode("-", $parts[2]) as $li ){
    $li = trim($li);
    if( strlen($li) == 0 ){
      continue;
    }
    $lis[] = "<li>" . trim($li) . "</li>";  
  }
  $lis = join("\n", $lis);
  
$divHtml = <<<EOF

    <div class="jumbotron vertical-center slide-container">
      <div class="container">
        <div class="row">
        
          <div class="col-md-8 col-md-offset-2 col-sm-12">
          
            <div class="inner">
            
              <div class="title">
                <div class="name">$title</div>
                <div class="date-line">$date</div>
              </div>               
                                      
              <div class="row">
                <div class="col-md-4">
                  <img src="images/$img" class="thumbnail" />
                </div>
                <div class="col-md-8">
                  <ul class="cliffnotes">
                    $lis
                  </ul>
                  <div class="text-right">
                    <a href="$link" class="btn btn-primary" target="_blank">
                      Learn More <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                  </div>
                </div>              
              </div>              
            </div>            
          </div>                                                                
        </div>
      </div>
    </div>  
  
EOF;

$html .= "\n\n" . $divHtml;

}

$html .= "\n<script>var MAX_SLIDES = " . $i . ";</script>";
file_put_contents("content.html", $html);