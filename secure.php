<?
/*
 * Copyright 2014 ViralParse, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
 
 /************************ DO NOT CHANGE ANYTHING BELOW ***************************/
 /************* CODE BELOW USED TO CONNECT WITH VIRALPARSE.COM SERVER *************/
 
 
 $access = get_option('wpp_access_code', 'NOWPP');
 if(VPconnextion() == 'FAILED'){
 delete_option('wpp_access_code');
 }
 
 function VPconnextion(){
 $options  = array('http' => array('user_agent' => 'ViralParse UserAgent 1.0'));
 $context  = stream_context_create($options);
 $access = get_option('wpp_access_code');
 $RESULT = file_get_contents('http://viralparse.com/root/?func=check&access='.$access, false, $context);
 if(empty($RESULT)){
 $RESULT = file_get_contents('http://api.viralparse.com/?func=check&access='.$access, false, $context);
 }
 return $RESULT;
 }
 
 function VPregister($email, $title, $url, $fullname){
 $options  = array('http' => array('user_agent' => 'ViralParse UserAgent 1.0'));
 $context  = stream_context_create($options);
 $RESPONSE = file_get_contents('http://viralparse.com/root/?func=new&email='.trim(urlencode($email)).'&title='.trim(urlencode($title)).'&fullname='.trim(urlencode($fullname)).'&url='.trim(base64_encode($url)), false, $context);
 if(empty($RESPONSE)){
 $RESPONSE = file_get_contents('http://api.viralparse.com/?func=new&email='.trim(urlencode($email)).'&title='.trim(urlencode($title)).'&fullname='.trim(urlencode($fullname)).'&url='.trim(base64_encode($url)), false, $context);
 }
 return $RESPONSE;
 }
 
 function VPconnect($access){
 $options  = array('http' => array('user_agent' => 'ViralParse UserAgent 1.0'));
 $context  = stream_context_create($options);
 $ISCONNECTED = file_get_contents('http://viralparse.com/root/?func=connect&access='.$access, false, $context);
 if(empty($ISCONNECTED)){
 $ISCONNECTED = file_get_contents('http://api.viralparse.com/?func=connect&access='.$access, false, $context);
 }
  return $ISCONNECTED;
 }
 
 function VPcreate($url, $title, $pic, $desc, $sn, $access){
 $options  = array('http' => array('user_agent' => 'ViralParse UserAgent 1.0'));
 $context  = stream_context_create($options);
 $ISCREATED = file_get_contents('http://viralparse.com/root/?func=create&url='.urlencode($url).'&title='.base64_encode($title).'&picture='.urlencode($pic).'&description='.base64_encode($desc).'&social='.base64_encode($sn).'&access='.$access, false, $context);
 if(empty($ISCREATED)){
 $ISCREATED = file_get_contents('http://api.viralparse.com/?func=create&url='.urlencode($url).'&title='.base64_encode($title).'&picture='.urlencode($pic).'&description='.base64_encode($desc).'&social='.base64_encode($sn).'&access='.$access, false, $context);
 }
 return $ISCREATED;
 }
 
 function VPlinks($access){
 $options  = array('http' => array('user_agent' => 'ViralParse UserAgent 1.0'));
 $context  = stream_context_create($options);
 $GETLINKS = file_get_contents('http://viralparse.com/root/?func=links&access='.$access, false, $context);
 if(empty($GETLINKS)){
 $GETLINKS = file_get_contents('http://api.viralparse.com/?func=links&access='.$access, false, $context);
 }
 return $GETLINKS;
 }
?>