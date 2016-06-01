<?php

                        // echo "flexibility=".$shift_flexibility;
					  //to findout total working and short/excess hours
					 if($dept[$id]=='Security')
					   {
						   $shifthours=8;
						   //$shiftduration=8;
						   $shift_in=$ins;
						   $shift_out=date("Y-m-d H:i:0",strtotime("$shift_in+ 8 Hours"));
						   //echo $shift_in."-".$shift_out;
					   }
					  $insdisplay=$ins;
					  $outsdisplay=$outs;
					  if(strtotime($ins)==strtotime($outs))
					  {
					  $ins=$shift_in;
					  $outs=$shift_in;
					  
					  }
                      elseif(strtotime($ins)<strtotime($shift_in))
					  {
					  $ins=$shift_in;
					  }
					  elseif(strtotime($outs)>strtotime($shift_out))
					  {
					  $outs=$shift_out;
					  }
					  else
					  {}
					  
				 // echo $ins."-".$outs;
				 
					  $total_work_min=ceil((strtotime($outs)-strtotime($ins))/60);
			          $dur=round((strtotime($outs)-strtotime($ins))/(60*60),2);
					  					  
			          $mins=floor(((strtotime($outs)-strtotime($ins))/60)-(floor($dur)*60));
			          $hrs=floor($dur);
					  //$shifthours=$workdur[$id];	
					  //echo $shifthours;
					  //echo round((strtotime($outs)-strtotime($ins))/(60));
					  $shortdur=($shifthours*60)-round((strtotime($outs)-strtotime($ins))/(60));
					  //echo "$shortdur";
					  //to get excess hours
					  $odcount=0;
					  $dur11=0;
					  if($shortdur>0 or $shortdur<0)
					  {
					  $qryod1=mysql_query("select * from employee_request where (ER_UserId='$id') and (ER_RequestTimeIn BETWEEN '$starttime' and '$endtime')");
    				  $numod1=mysql_num_rows($qryod1);
					  //echo "num=$numod1";
					  
					  if($numod1>0)
					    {
						  //$odcount=1;
						  $oddur=0;
					      while($objod1=mysql_fetch_object($qryod1))
					     {
					      $paidstatus2=$objod1->ER_ApprovedPaidStatus;
						  //echo "paid=".$paidstatus2;
						  if($paidstatus2=="Y")
						  {
						  $odTimeIn1[$odcount]=$objod1->ER_RequestTimeIn;
						  $odTimeOut1[$odcount]=$objod1->ER_RequestTimeOut;
						  $paidstatus1[$odcount]=$objod1->ER_ApprovedPaidStatus;
						  $odApprovedTimeIn1[$odcount]=$objod1->ER_ApprovedTimeIn;
						  $odApprovedTimeOut1[$odcount]=$objod1->ER_ApprovedTimeOut;
						  $start[$odcount]=strtotime($odApprovedTimeIn1[$odcount]);
						  $end[$odcount]=strtotime($odApprovedTimeOut1[$odcount]);
						  if(strtotime($odApprovedTimeOut1[$odcount])>=strtotime($shift_out))
						  {
							  $end[$odcount]=strtotime($shift_out);
							 // echo"test";
							 
						  }
						  if((strtotime($odApprovedTimeIn1[$odcount]))<=strtotime($shift_in))
						  {
							  $start[$odcount]=strtotime($shift_in);
							 // echo"test";
							 
						  }
						  $durs= ((($start[$odcount])-($end[$odcount]))/60);
						  //echo  $durs;
						  $dur1=((strtotime($odApprovedTimeOut1[$odcount])-strtotime($odApprovedTimeIn1[$odcount]))/60);
						  //echo"dur=$dur";
						  $paidstatus1=$objod1->ER_ApprovedPaidStatus;
						  $shortdur=$shortdur-$dur1;
						  $od=10;
						  $odcount++;
						  }
						 
						  else
						  {
						   if($paidstatus2=="N")
						   {
						  $odTimeIn2[$odcount]=$objod1->ER_RequestTimeIn;
						  $odTimeOut2[$odcount]=$objod1->ER_RequestTimeOut;
						  $odApprovedTimeIn1[$odcount]=$objod1->ER_ApprovedTimeIn;
						  $odApprovedTimeOut1[$odcount]=$objod1->ER_ApprovedTimeOut;
						  $start1[$odcount]=strtotime($odApprovedTimeIn1[$odcount]);
						  $end1[$odcount]=strtotime($odApprovedTimeOut1[$odcount]);
						  if($shortdur<=15)
						  {
						  $dur2=((strtotime($odApprovedTimeOut1[$odcount])-strtotime($odApprovedTimeIn1[$odcount])));
						  }
						  elseif($start1[$odcount]>=strtotime($ins) and $end1[$odcount]<=strtotime($outs))
						  {
						   $dur2=((strtotime($odApprovedTimeOut1[$odcount])-strtotime($odApprovedTimeIn1[$odcount])));
						  }
						  else
						  {
						  $dur2=0;
						  }
						   $dur11=$dur11+$dur2;
						   
						   if($summary==0)
						    {
						    //echo  "<font color='red'>". $dur2."</font>" ;
						    }
						   }
						   else
						   {
						   if($summary==0)
						    {
						     echo  "<font color='red'>OD".$objod1->ER_Status ." $dur2</font>" ;
                            }
						   }
						  }
						 
                         }
						}
						
					  
					  }
//					  $odcount++;
					  $start[$odcount]=strtotime($ins);
					  $end[$odcount]=strtotime($outs);
					  if($end[$odcount]<$start[$odcount])
					  {
						$end[$odcount]=$start[$odcount];  
					  }
					  //$start=array("5","4","8");
                      //$end=array("6","8","9");
					  
					  $dur=compareTimes($start,$end);
					  //echo "dur=".$dur;
					  $start1[100]=strtotime($ins);
					  $end1[100]=strtotime($outs);
					  /*if($end1[100]<$start1[100])
					  {
						  $end1[100]=$start1[100];
				      }*/
					  //echo date('h:i:s',$start1[100])."-".date('h:i:s',$end1[100]/3600);
					// $dur11=compareNPTimes($start1,$end1);
					  //echo "floor(($shifthours*60)-($dur))";
					  //$shifthours=(strtotime(shift_out)-strtotime(shift_in))/60;
					  $shortdur=round(($shifthours*60)-$dur);
					  //echo "dur=".$shortdur;
					  $shortday_day_wise[strtotime($dates)]=$shortdur;
					  //$shortdur=$shortdur+$dur11;
					  unset ($start);
					  unset ($end);
					  unset ($start1);
					  unset ($end1);
					  $weekday=date('w',strtotime($dates));
					  //echo $holiday;
					if($holiday==1)
					{
					 $shortdur=0;
					}
					
					  if($shortdur<=16)
					  {
					  $shortdur=0;
					  }
					 //echo $shortdur;
					  $shortdur= $shortdur+round($dur11/60);
					  //echo "shortdur=".$shortdur;
					 // echo $shift_duration;
					  	if($shift_duration=="" or $shift_duration=="0")
						{
							$shift_duration=8;
						}
						if($shift_duration<0)
						{
							$shift_duration=$shift_duration*-1;
						}
					   
					   if($shortdur>($shift_duration*60))
					  {
						 $shortdur=$shift_duration*60;
					  }
					
					  /*
					  if($shortdur<0)
					  {
					  $shortdur=0;
					  $excessdur=($shortdur)*(-1);
					  $excesshrs=floor($excessdur/60);
					  $excessmins=$excessdur-($excesshrs*60);
					  $totexcessmins=$excessmins+$totexcessmins;
					  //echo "<td>excess=$excessmins</td>";
					  }
					  else
					  {
					  $excessdur=0;
					  $excesshrs=0;
					  $excessmins=0;
					  }
					  */
					  if($shifthours==9)
					  {
					  
					   if($shortdur>480)
					   {
					   $shorthrs--;
					   $shortdur=$shortdur-60;
					   //$shortmins=0;
					   }
					  }
					  if($shortdur>59)
					  {
					      $shorthrs=floor($shortdur/60);
						  $shortmins=$shortdur-($shorthrs*60);
					  }
					  
					  elseif($shortdur>0)
					  {
					  $shortmins=$shortdur;
					  }
					  //echo $shorthrs;
					  //to calculate late comings  more than 15 minutes
					  if($shortdur>=16)
					  {
					  $shortdursal=$shortdur;
					  $totsalded=$totsalded+$shortdursal;
					  }
					 /* if($shortdur<15 and $shortdur>0)
					  {
					  $concession_upto_60minutes=$concession_upto_60minutes+$shortdur;
					  }
			          */
					  
					  $intim=substr($insdisplay,10,6);
			          $outtim=substr($outsdisplay,10,6);
					  //for frdiays
					  if($holiday==1)
	  			        {
						 if($summary==0)
						 {
						   echo "<font bgcolor='red'>".$intim."</br>".$outtim." </br></font>";
						 }
	  			          $fri[$id]++;
			              $work_hrs_fri=$dur+$work_hrs_fri;
						  if($nightshift==1){}
						  else{$f++;}
			        	  
	  			        }
			       	    else
	  			        { 
						  		  
						  if($summary==0)
						   {
						    if($od==10)
							{
							 for($i=0;$i<$odcount;$i++)
							 {
							 echo "<font size='0' color='green'>".substr($odApprovedTimeIn1[$i],11,5)."-".substr($odApprovedTimeOut1[$i],11,5)."</font> </br>";
							 }
							}
			                echo "<font size='0'>".$intim."-".$outtim."</font> </br>";
						   }
						 if($excessdur>0)
						 {
						 $totexcessdur=$excessdur+$totexcessdur;
						 
						  if($summary==0)
						   {
						 	//echo "<font color='blue'>$excesshrs:$excessmins</font></br>";
						   }						 
						 }
						 
						 if($shortdur>=60)
						 {
						   if($summary==0)
						   {
						    
						    if($od==1 or $od==10)
							{
							 if($lockstatus=="Y")
							{
							echo "<font color='red'>".$shorthrs.":".$shortmins."</font>";
							}
							else
							{
							echo "<a href='employeeattendancerequest.php?UserId=$id&dates=$dates&intime=$intim&outtime=$outtim&hidAction=new&nightshift=$nightshift'><font color='red'>".$shorthrs.":".$shortmins."</font></a>";}
						    }
							else
							{
							if($lockstatus=="Y")
							{
							echo "<font color='red'>".$shorthrs.":".$shortmins."</font>";
							}
							else
							{
						   echo "<font color='red'><a href='employeeattendancerequest.php?UserId=$id&dates=$dates&intime=$intim&outtime=$outtim&hidAction=new&nightshift=$nightshift'><font color='red'>".$shorthrs.":".$shortmins."</font></a></font>";
						    }
						    }
						   }
						$totshortdur=$totshortdur+$shortdur;
						
						 }
						 elseif($shortdur>0)
						 {
						 $shorthrs=0;
						   
						  
 						   if($summary==0)
						   {
						    if($lockstatus=="Y")
						   {
						   echo "<font color='red'>".$shorthrs.":".$shortmins."</font>";
						   }
						   else
						   {
						   echo "<font color='red'><a href='employeeattendancerequest.php?UserId=$id&dates=$dates&intime=$intim&outtime=$outtim&hidAction=new&nightshift=$nightshift'><font color='red'>".$shorthrs.":".$shortmins."</font></a></font>";
						   }
						   }
						$totshortdur=$totshortdur+$shortdur;
						
						 }
						 $work_hrs=($hrs*60+$mins)+$work_hrs;
			      	   }
   			      $c++;
				  $presntdays[strtotime($dates)]=1;
   			      
					  ?>