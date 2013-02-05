/* In PHP */
$encodedPolicy = json_encode( array(
              "expiration" => "2011-4-22T13:54:23.000Z",
              "conditions" => array(
                  0 => array( "acl" => "public-read" ),
                  1 => array( "bucket" => "your-bucket" ),
                  2 => array( "x-amz-meta-sig" => 'some meta signature to ensure authentic requests'),
                  3 => array( "redirect" => $'URL to redirect a success request (its doesnt matter)' ),
                  4 => array( "key" => "the S3 key for the file (the S3 filename)" ),
                  5 => array( "Filename" => "The original filename of the file. THIS IS IMPORTANT." )
              ),
            )
);
          
$encodedPolicy = base64_encode( $encodedPolicy );
$s3 = new S3( sfConfig::get("app_amazon_s3_id"), sfConfig::get("app_amazon_s3_secret") );
list($dist, $hmacSignature) = explode(":", $s3->__getSignature( $encodedPolicy ));

/* END PHP */

            var swfConfig = { 
                    'AWSAccessKeyId': 'your amazon ID',
                    'acl': 'public-read',
                    'key': 'the S3 key for the file (the S3 filename)',
                    'policy': '<?php echo $encodedPolicy?>',
                    'signature': '<?php echo $signature'?>,
                    'redirect': 'URL to redirect a success request (its doesnt matter)',
                    'x-amz-meta-sig': 'some meta signature to ensure authentic requests',
            };

            // this line sets the post params so that SWFUpload will send the additional fields when it uploads the file.
            $.swfu.setPostParams( swfConfig );

