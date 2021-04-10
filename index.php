<?php


    require_once  './CoreAccess.php';


    $coreObject =  new \CoreAccess();

    $token = 'eyJraWQiOiI4NkQ4OEtmIiwiYWxnIjoiUlMyNTYifQ.eyJpc3MiOiJodHRwczovL2FwcGxlaWQuYXBwbGUuY29tIiwiYXVkIjoiY29tLmFsbGZvb3RiYWxsYXBwLm5ld3MiLCJleHAiOjE2MTgwMzIwNjcsImlhdCI6MTYxNzk0NTY2Nywic3ViIjoiMDAxOTExLjdkYjU4OWQyNDE3YTRlMmZhYjI5NDlkNmM5NDI1ZmNhLjA4NTAiLCJjX2hhc2giOiI1R0hVLWlqak1CV0VaTmc5cklibVNnIiwiZW1haWwiOiJoZmJkcnhnZ2Z3QHByaXZhdGVyZWxheS5hcHBsZWlkLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjoidHJ1ZSIsImlzX3ByaXZhdGVfZW1haWwiOiJ0cnVlIiwiYXV0aF90aW1lIjoxNjE3OTQ1NjY3LCJub25jZV9zdXBwb3J0ZWQiOnRydWV9.Wwh94qDYPjlxZQmz_NWkLEtwKDV4Up6h1Iq_PJIDfElqnuaVyGX3XsyWG-1H2hJxr6LHCXFyO8zTSabvwMlxqIHGVzmDd8BeRE9WfZKFa9_SoT0uaDfUG12a_OuftnT66i_La1r8J7yFzxZU1G0RcwMPSNn80R46CQ3oedSga0lqXkPaH0Pr7gLOW74FyIdb_k3G-hPQEgHtTmtBYHhmQXbDpffRu-Hxofi3qaac1G3RHskJV0_qOZr6KbbSBbwPAtwgXttvo6yhudidl5JOSpPhvLxXtF8oTypLVp42TAIMhLNFyHD6WsTztil4z2gys0Rtuz7V0kMHDhIVNsF3_g';
    $openId = '001911.7db589d2417a4e2fab2949d6c9425fca.0850';
   //上面两个参数接受客户端的传递
   $res =  $coreObject->index($token,$openId);

   if($res){
       echo '验证通过';
   }else{
       echo '验证失败';
   }



