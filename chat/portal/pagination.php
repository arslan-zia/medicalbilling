<?php 						if($page == 0) 
								$page = 1;	
												
							$prev 		= 	$page - 1;							
							$next 		= 	$page + 1;							
							$lastpage 	= 	ceil($total_pages/$limit);		
							$lpm1 		= 	$lastpage - 1;					
							$pagination = 	"";
			
							if($lastpage > 1)
							{	
								//$pagination .= "<div align=\"right\" class=\"pagination\"><ul id=\"pagination-digg\">";
						
								if ($page > 1) 
									$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=$prev\">‹</a></li>";
								else
									$pagination.= "<li class=\"active\"><a href=\"#\">‹</a></li>";	
								
							
								if ($lastpage < 7 + ($adjacents * 2))	
								{	
									for ($counter = 1; $counter <= $lastpage; $counter++)
									{
										if ($counter == $page)
											$pagination.= "<li class=\"active\"><a class=\"legitRipple\" href=\"#\">$counter</a></li>";
										else
											$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=$counter\">$counter</a></li>";					
									}
								}
								elseif($lastpage > 5 + ($adjacents * 2))	
								{
									if($page < 1 + ($adjacents * 2))		
									{
										for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
										{
											if ($counter == $page)
												$pagination.= "<li class=\"active\"><a class=\"legitRipple\" href=\"#\">$counter</a></li>";
											else
												$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=$counter\">$counter</a></li>";					
										}
										$pagination.= "<li class=\"disabled\"><a class=\"legitRipple\" href=\"#\">...</a></li>";
										$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=$lpm1\">$lpm1</a></li>";
										$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=$lastpage\">$lastpage</a></li>";		
									}
									
									elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
									{
										$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=1\">1</a></li>";
										$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=2\">2</a></li>";
										$pagination.= "<li class=\"disabled\"><a class=\"legitRipple\" href=\"#\">...</a></li>";
										for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
										{
											if ($counter == $page)
												$pagination.= "<li class=\"active\">$counter</li>";
											else
												$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=$counter\">$counter</a></li>";					
										}
										$pagination.= "<li class=\"disabled\"><a class=\"legitRipple\" href=\"#\">...</a></li>";
										$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=$lpm1\">$lpm1</a></li>";
										$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=$lastpage\">$lastpage</a></li>";		
									}
								
									else
									{
										$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=1\">1</a></li>";
										$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=2\">2</a></li>";
										$pagination.= "<li class=\"disabled\"><a class=\"legitRipple\" href=\"#\">...</a></li>";
										for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
										{
											if ($counter == $page)
												$pagination.= "<li class=\"active\"><a class=\"legitRipple\" href=\"#\">$counter</a></li>";
											else
												$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=$counter\">$counter</a></li>";					
										}
									}
								}
								
								if ($page < $counter - 1) 
									$pagination.= "<li><a class=\"legitRipple\" href=\"$targetpage&page=$next\">›</a></li>";
								else
									$pagination.= "<li class=\"disabled\"><a href=\"#\">›</a>";
								//$pagination.= "</ul></div>\n";		
							}
?>							