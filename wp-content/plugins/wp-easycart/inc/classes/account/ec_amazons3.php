<?php
class ec_amazons3 {
	public function download_file( $file_name ) {
		if ( get_option( 'ec_option_amazon_bucket_region' ) ) {
			$client = Aws\S3\S3Client::factory(
				array(
					'signature' => 'v4',
					'version' => 'latest',
					'region'  => mb_convert_encoding( stripslashes( get_option( 'ec_option_amazon_bucket_region' ) ), 'UTF-8' ),
					'credentials' => array(
						'key' 		=> mb_convert_encoding( stripslashes( get_option( 'ec_option_amazon_key' ) ), 'UTF-8' ),
						'secret' 	=> mb_convert_encoding( stripslashes( get_option( 'ec_option_amazon_secret' ) ), 'UTF-8' ),
					),
				)
			);
		} else {
			$client = Aws\S3\S3Client::factory(
				array(
					'signature' => 'v4',
					'version' => 'latest',
					'region'  => '',
					'credentials' => array(
						'key' 		=> mb_convert_encoding( stripslashes( get_option( 'ec_option_amazon_key' ) ), 'UTF-8' ),
						'secret' 	=> mb_convert_encoding( stripslashes( get_option( 'ec_option_amazon_secret' ) ), 'UTF-8' ),
					),
				)
			);
		}

		$command = $client->getCommand(
			'GetObject',
			array(
				'Bucket' => stripslashes( get_option( 'ec_option_amazon_bucket' ) ),
				'Key' => $file_name,
				'ResponseContentDisposition' => 'attachment; filename="' . $file_name . '"',
			)
		);
		$signedRequest = $client->createPresignedRequest( $command, '+100 seconds' );
		$signedUrl = (string) $signedRequest->getUri();
		header( "location:" . $signedUrl );
		die();
	}

	public function get_aws_files() {
		$returnArray = array();
		if ( get_option( 'ec_option_amazon_bucket_region' ) ) {
			$client = Aws\S3\S3Client::factory(
				array(
					'signature' => 'v4',
					'version' => 'latest',
					'region'  => mb_convert_encoding( stripslashes( get_option( 'ec_option_amazon_bucket_region' ) ), 'UTF-8' ),
					'credentials' => array(
						'key' 		=> mb_convert_encoding( stripslashes( get_option( 'ec_option_amazon_key' ) ), 'UTF-8' ),
						'secret' 	=> mb_convert_encoding( stripslashes( get_option( 'ec_option_amazon_secret' ) ), 'UTF-8' ),
					),
				)
			);
		}else{
			$client = Aws\S3\S3Client::factory(
				array(
					'signature' => 'v4',
					'version' => 'latest',
					'region'  => '',
					'credentials' => array(
						'key' 		=> mb_convert_encoding( stripslashes( get_option( 'ec_option_amazon_key' ) ), 'UTF-8' ),
						'secret' 	=> mb_convert_encoding( stripslashes( get_option( 'ec_option_amazon_secret' ) ), 'UTF-8' ),
					),
				)
			);
		}
		$result = $client->listObjects( array( 'Bucket' => stripslashes( get_option( 'ec_option_amazon_bucket' ) ) ) );
		foreach ( $result['Contents'] as $object ) {
			if ( substr( $object['Key'], 0, 5 ) != "logs/" ) {
				$returnArray[] = $object['Key'];
			}
		}
		return $returnArray;
	}
}
