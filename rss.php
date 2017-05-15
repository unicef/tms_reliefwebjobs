<?php		
		header('Content-type: text/xml');
 		echo '<?xml version="1.0" encoding="utf-8"?>';
		echo'<channel xmlns:job="http://pageuppeople.com/">';
		$xml = new DOMDocument('1.0', 'UTF-8');
                $xml->load('http://careers.pageuppeople.com/671/cw/en-us/rss');
                $feed = array();
                foreach ($xml->getElementsByTagName('item') as $node) {
                                $item = array ( 
                                                'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                                                'desc' => $node->getElementsByTagNameNS("http://pageuppeople.com/","description")->item(0)->nodeValue,
						'field_job_type' => $node->getElementsByTagNameNS("http://pageuppeople.com/","workType")->item(0)->nodeValue,
						'category' => $node->getElementsByTagNameNS("http://pageuppeople.com/","category")->item(0)->nodeValue,
						'job_number' => $node->getElementsByTagNameNS("http://pageuppeople.com/","refNo")->item(0)->nodeValue,
                                                'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                                                'pubDate' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
                                                'closeDate' => $node->getElementsByTagName('closingDate')->item(0)->nodeValue,
						'field_country' => $node->getElementsByTagNameNS("http://pageuppeople.com/","location")->item(0)->nodeValue,
						'field_city' => $node->getElementsByTagNameNS("http://pageuppeople.com/","businessLayer1")->item(0)->nodeValue,
                                                );
                                array_push($feed, $item);
                }
                $limit = 15;
                for($x=0;$x<$limit;$x++) {
                                echo '<item>';
                                $title = str_replace("&", ' &amp; ', $feed[$x]['title']);
                                $link = $feed[$x]['link'];
				$desc = $feed[$x]['desc'];
                          	$desc = str_replace("&nbsp;", '', $desc);
				$desc = str_replace("<br>;", '', $desc);
 				$field_country = $feed[$x]['field_country'];
				$field_country = substr($field_country, strrpos($field_country, "|")+1);
				$field_city = $feed[$x]['field_city'];
				$field_job_type = $feed[$x]['field_job_type'];
				$category = $feed[$x]['category'];
				$category =  split('[|,]', $category); 
				$field_job_experience = $feed[$x]['category'];
				$field_job_experience = substr($field_job_experience, strrpos($field_job_experience, "Job Level|")+10);
				$job_number = $feed[$x]['job_number'];
                                $pubDate = date('c', strtotime($feed[$x]['pubDate']));
                                $closeDate = date('c', strtotime($feed[$x]['closeDate']));
				echo '<title>'.$title.'</title>';
                                echo '<pubDate>'.$pubDate.'</pubDate>';
                                echo '<field_job_closing_date>'.$closeDate.'</field_job_closing_date>';
                                echo '<link>http://www.unicef.org/about/employ/?job='.$job_number.'</link>';
				$csv = array_map('str_getcsv', file('countries.csv'));
				$findName= $field_country;
				foreach($csv as $values)
				{
 					 if($values[1]==$findName)  
   					 echo '<field_country>' .$values[2]. '</field_country>';       
				};
				$csv = array_map('str_getcsv', file('countries.csv'));
				$findName= $field_country;
				foreach($csv as $values)
				{
				  if($values[1]==$findName)  
				    echo '<field_city>' .$values[3]. '</field_city>';       
				};
				$csv = array_map('str_getcsv', file('job_type.csv'));
				$findName= $field_job_type;
				foreach($csv as $values)
				{
				  if($values[0]==$findName)  
				    echo '<field_job_type>' .$values[1]. '</field_job_type>';       
				};
				$csv = array_map('str_getcsv', file('job_category.csv'));
				$findName= $category[1];
				foreach($csv as $values)
				{
				  if($values[0]==$findName)  
				    echo '<field_career_categories>' .$values[1]. '</field_career_categories>';  
				};
				$csv = array_map('str_getcsv', file('job_levels.csv'));
				$findName= $field_job_experience;
				foreach($csv as $values)
				{
				  if($values[0]==$findName)  
				    echo '<field_job_experience>' .$values[1]. '</field_job_experience>';  
				};
				echo '<field_source>1979</field_source>';
				$csv = array_map('str_getcsv', file('job_theme.csv'));
				$findName= $category[1];
				foreach($csv as $values)
				{
				  if($values[0]==$findName)  
				    echo '<field_theme>' .$values[1]. '</field_theme>';  
				};
                                echo strip_tags('<body>'.$desc.'</body>', '<p><body><strong><li>');
 				echo '<field_how_to_apply><strong>UNICEF is committed to diversity and inclusion within its workforce, and encourages qualified female and male candidates from all national, religious and ethnic backgrounds, including persons living with disabilities, to apply to become a part of our organization. To apply, click on the following link </strong>http://www.unicef.org/about/employ/?job='.$job_number.'</field_how_to_apply>';
                                echo '</item>';
                }

                echo '</channel>';

?>
