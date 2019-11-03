		var ajaxCall;

		Array.prototype.remove = function(value){
			var index = this.indexOf(value);
			if(index != -1){
				this.splice(index, 1);
			}
			return this;
		};
		function enableTextArea(bool){
			$('#listcc').attr('disabled', bool);
			$("#socks").attr('disabled', bool);
		}
		function checkerpro_liveUp(){
			var count = parseInt($('#checkerpro_live_count').html());
			count++;
			$('#checkerpro_live_count').html(count+'');
		}
		
		function sothutuUp(){
			var count = parseInt($('#sothutu').html());
			count++;
			$('#sothutu').html(count+'');
					
		}
		
		function sothutuDown(){
			var count = parseInt($('#sothutu').html());
			count--;
			$('#sothutu').html(count+'');
					
		}
		
		
		function checkerpro_dieUp(){
			var count = parseInt($('#checkerpro_die_count').html());
			count++;
			$('#checkerpro_die_count').html(count+'');
		}
		
		function checkerpro_die_checkUp(){
			var count = parseInt($('#checkerpro_die_check_count').html());
			count++;
			$('#checkerpro_die_check_count').html(count+'');
		}
		
		

		function stopLoading(bool){
			$('#loading').attr('src', 'images/clear.gif');
			var str = $('#checkStatus').html();
			$('#checkStatus').html(str.replace('Checking','Stopped'));
			enableTextArea(false);
			$('#submit').attr('disabled', false);
			$('#stop').attr('disabled', true);
			if(bool){
				alert('Done');
			}else{
				ajaxCall.abort();
			}
			updateTitle('Pro Account Checker');
		}
		function updateTitle(str){
			document.title = str;
		}
		function updateTextBox(mp,sock){

			var listcc = $('#listcc').val().split("\n");
			var socks=$("#socks").val().split("\n");
			
			listcc.remove(mp);
			socks.remove(sock);
			
			$('#listcc').val(listcc.join("\n"));
			$('#socks').val(socks.join("\n"));
		}
		
		function updatesock(sock){
			var socks=$("#socks").val().split("\n");
			socks.remove(sock);
			$('#socks').val(socks.join("\n"));
		}
		
		function updateTextBoxNOSOCK(mp){
			var listcc = $('#listcc').val().split("\n");
			listcc.remove(mp);
			$('#listcc').val(listcc.join("\n"));
		}
		
		
	function CheckPro(lstMP,lstsock,curMP,cursock,delim,cEmail,maxFail,failed){
		
		//KIEM TRA XEM CO PHAI RANDOM SOCK KHONG
			var randomsock = $('#randomsock').is(':checked') ? 1 : 0;
			
			if(lstMP.length<1||lstsock.length<1 ||curMP>=lstMP.length||cursock>=lstsock.length){
				stopLoading(true);
				return false;
			}
			if(failed>=maxFail){
			cursock++;
CheckPro(lstMP,lstsock,curMP,cursock,delim,cEmail,maxFail,0);
return false;
			}
			
			//updateTextBox(lstMP[curMP],lstsock[cursock]);
			
			//UPDATE RANDDOM SOCK
			if(randomsock==1) {
				//CHI UPDATE MAILPASS
			updateTextBoxNOSOCK(lstMP[curMP]);
			//updateTextBox(lstMP[curMP],lstsock[cursock]);
			}
			else {
			updateTextBox(lstMP[curMP],lstsock[cursock]);	
			}
			
			
			
			
			
			var webcheck=$("#webcheck").val();
			var sleep = $('#sleep').val();
			
			$('#checktongso').html(lstMP.length);
			
			ajaxCall = $.ajax({
				url: 'thuvien.php',
				dataType: 'json',
				cache: false,
				type: 'POST',
				beforeSend: function (e) {
					updateTitle(lstMP[curMP] + ' - Pro Account Checker');
					$('#checkStatus').html('Checking: ' + cursock + '|' +lstsock.length + '|' +  lstsock[cursock] + '|' +  lstMP[curMP]);
					sothutuUp();
					
					var percent1 = $("#sothutu").html()/lstMP.length * 100;
					var percent = percent1.toFixed(2)+"%";
					
					document.getElementById("progress").innerHTML="<div style=\"width:"+percent+";background-image:url(images/pbar-ani.gif);\">&nbsp;</div>";
					document.getElementById("information").innerHTML=innerHTML="Hoàn thành "+percent;
					
					$('#loading').attr('src', 'images/loading.gif');
				},
				data:"ajax=1&hamxuly=checkwebsite&sock="+encodeURIComponent(lstsock[cursock])+"&listcc="+encodeURIComponent(lstMP[curMP])+"&delim="+encodeURIComponent(delim)+"&email="+cEmail+'&webcheck='+webcheck+'&sleep='+sleep,
				success:function (data){
					
					switch(data.thongbaoloi){
						case -1:
							curMP++;
							$('#wrong').append(data.msg+'<br />');
							break;
						case 1:
						case 3: 
							
							//UPDATE RANDDOM SOCK
			if(randomsock==1) {
				//CHI UPDATE MAILPASS
			updatesock(lstsock[cursock]);
			//	cursock++;
			//updateTextBox(lstMP[curMP],lstsock[cursock]);
			}
			else {
			cursock++;
			}
							
							
							$('#badsock').append(data.msg+'<br />');
							sothutuDown();
							break;
						case 2:
							curMP++;
							$('#checkerpro_die').append(data.msg+'<br />');
							failed++;
							checkerpro_dieUp();
							break;
						case 4:
							
							
									//UPDATE RANDDOM SOCK
			if(randomsock==1) {
				//CHI UPDATE MAILPASS
			updatesock(lstsock[cursock]);
			//	cursock++;
			//updateTextBox(lstMP[curMP],lstsock[cursock]);
			}
			else {
			cursock++;
			}
			
			
							$('#checkerpro_die_check').append(data.msg+'<br />');
							sothutuDown();
							checkerpro_die_checkUp();							
							break;	
						case 0:
							curMP++;
							$('#checkerpro_live').append(data.msg+'<br />');
							checkerpro_liveUp();
							break;
					}
					
					document.getElementById('kqTong').style.display = 'block';
					document.getElementById('kqlive').style.display = 'block';
					document.getElementById('kqdie').style.display = 'block';
					document.getElementById('kqunk').style.display = 'block';
document.getElementById('kqTong').innerHTML = "<font color='#000'><b>All: " + parseInt($('#checktongso').html()) + "</b></font>";
document.getElementById('kqlive').innerHTML = "<font color='#fff'><b>Live: " + parseInt($('#checkerpro_live_count').html()) + "</b></font>";
document.getElementById('kqdie').innerHTML = "<font color='#fff'><b>Die: " + parseInt($('#checkerpro_die_count').html()) + "</b></font>";
document.getElementById('kqunk').innerHTML = "<font color='#fff'><b>Un+Inv: " + parseInt($('#checkerpro_die_check_count').html()) + " </b></font>";




//UPDATE RANDDOM SOCK
			if(randomsock==1) {
			var capnhatsock=$("#socks").val().match(/\d{1,3}([.])\d{1,3}([.])\d{1,3}([.])\d{1,3}((:)|(\s)+)\d{1,8}/g);	
			//RAND SOCK
			var randsock = Math.floor((Math.random() * (parseInt(lstsock.length)-1)) + 0);		
			///////////	
			
			if(lstMP.length<1||lstsock.length==1 ||curMP>=lstMP.length){
				stopLoading(true);
				return false;
			}
			CheckPro(lstMP,capnhatsock,curMP,randsock,delim,cEmail,maxFail,failed);
			
			
			}
			else {
			CheckPro(lstMP,lstsock,curMP,cursock,delim,cEmail,maxFail,failed);
			}
			
			
				//	CheckPro(lstMP,lstsock,curMP,cursock,delim,cEmail,maxFail,failed);
				}
			});
			return true;
		}
		
		
	function CheckProNOSOCK(lstMP, curMP,  delim, cEmail, maxFail, failed){
			
			if(lstMP.length<1 ||curMP>=lstMP.length){
				stopLoading(true);
				return false;
			}
			if(failed>=maxFail){
			
				CheckProNOSOCK(lstMP, curMP, delim, cEmail, maxFail, 0);
				return false;
			}
			updateTextBoxNOSOCK(lstMP[curMP]);
			
			var webcheck=$("#webcheck").val();
			var sleep = $('#sleep').val();
			
			$('#checktongso').html(lstMP.length);
			
			ajaxCall = $.ajax({
				url: 'thuvien.php',
				dataType: 'json',
				cache: false,
				type: 'POST',
				beforeSend: function (e) {
					updateTitle(lstMP[curMP] + ' - Pro Account Checker');
					$('#checkStatus').html('Checking: ' + lstMP[curMP]);	
				
					sothutuUp();	
					
					var percent1 = $("#sothutu").html()/lstMP.length * 100;
					var percent = percent1.toFixed(2)+"%";
					
					document.getElementById("progress").innerHTML="<div style=\"width:"+percent+";background-image:url(images/pbar-ani.gif);\">&nbsp;</div>";
					document.getElementById("information").innerHTML="Hoàn thành "+percent;
									
					$('#loading').attr('src', 'images/loading.gif');
				},
				data: 'ajax=1&hamxuly=checkwebsite&listcc='+encodeURIComponent(lstMP[curMP])
						+'&delim='+encodeURIComponent(delim)+'&email='+cEmail+'&webcheck='+webcheck+'&sleep='+sleep,
				success: function(data) {
					switch(data.thongbaoloi){
						case -1:
							curMP++;
							$('#wrong').append(data.msg+'<br />');
							break;
						case 1:
						case 3:
							$('#badsock').append(data.msg+'<br />');
							sothutuDown();
							break;
						case 4:							
							$('#checkerpro_die_check').append(data.msg+'<br />');
							sothutuDown();
							checkerpro_die_checkUp();
							break;		
							
						case 2:
							curMP++;
							$('#checkerpro_die').append(data.msg+'<br />');
							failed++;
							checkerpro_dieUp();
							break;
						case 0:
							curMP++;
							$('#checkerpro_live').append(data.msg+'<br />');
							checkerpro_liveUp();
							break;
					}
					
					document.getElementById('kqTong').style.display = 'block';
					document.getElementById('kqlive').style.display = 'block';
					document.getElementById('kqdie').style.display = 'block';
					document.getElementById('kqunk').style.display = 'block';
document.getElementById('kqTong').innerHTML = "<font color='#000'><b>All: " + parseInt($('#checktongso').html()) + "</b></font>";
document.getElementById('kqlive').innerHTML = "<font color='#fff'><b>Live: " + parseInt($('#checkerpro_live_count').html()) + "</b></font>";
document.getElementById('kqdie').innerHTML = "<font color='#fff'><b>Die: " + parseInt($('#checkerpro_die_count').html()) + "</b></font>";
document.getElementById('kqunk').innerHTML = "<font color='#fff'><b>Un+Inv: " + parseInt($('#checkerpro_die_check_count').html()) + " </b></font>";


					CheckProNOSOCK(lstMP, curMP, delim, cEmail, maxFail, failed);
				}
			});
			return true;
		}
		
			
		
		
		
		function filterMP(mp, delim){
			var mps = mp.split("\n");
			var filtered = new Array();
			var lstMP = new Array();
			for(var i=0;i<mps.length;i++){
				if(mps[i].indexOf('@')!=-1){
					var infoMP = mps[i].split(delim);
					for(var k=0;k<infoMP.length;k++){
						if(infoMP[k].indexOf('@')!=-1){
							var email = $.trim(infoMP[k]);
							var pwd = $.trim(infoMP[k+1]);
							if(filtered.indexOf(email.toLowerCase())==-1){
								filtered.push(email.toLowerCase());
								lstMP.push(email+'|'+pwd);
								break;
							}
						}
					}
				}
			}
			return lstMP;
		}
		
		
		
		
		
		
		function filterCC(cc, delim){
			var ccs = cc.split("\n");
			var filtered = new Array();
			var lstcc = new Array();
			for(var i=0;i<ccs.length;i++){
				
					var infocc = ccs[i].split(delim);
					for(var k=0;k<infocc.length;k++){
						
							//var type = $.trim(infocc[k]);
							var type = $.trim(infocc[k+1]);
							var ccn = $.trim(infocc[k+2]);
							var ccmon = $.trim(infocc[k+3]);
							var ccyear = $.trim(infocc[k+4]);
							var cvv = $.trim(infocc[k+5]);
							
							
							if(filtered.indexOf(type.toLowerCase())==-1){
								filtered.push(type.toLowerCase());
								// +'|'+

								lstcc.push(type+'|'+ccn+'|'+ccmon+'|'+ccyear+'|'+cvv);
								break;
							}
						
					}
				
			}
			return lstcc;
		}
		
		
		
		
		
		function filterCC2(cc){
var ccs=cc.split('\n');
var filtered= new Array();
var lstcc= new Array();
for(var i=0;i<ccs.length;i++){
if(ccs[i].length>0){
var variable2c=/(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})/g;
var variable2d=ccs[i].match(variable2c);
if(variable2d==null||!LuhnCheck(variable2d[0])){
continue ;
}

if(filtered.indexOf(variable2d[0])==-1){
filtered.push(variable2d[0]);
lstcc.push(ccs[i]);
}
}
}
return lstcc;
}

var LuhnCheck=(function (){
var variable2f=[0,2,4,6,8,1,3,5,7,9];
return function (variable26){
var variable30=0;
var variable31;
var variable32=false;
var variable33=String(variable26).replace(/[^\d]/g,"");
if(variable33.length==0){
return false;
}

for(var i=variable33.length-1;i>=0;--i){
variable31=parseInt(variable33.charAt(i),10);
variable30+=(variable32=!variable32)?variable31:variable2f[variable31];
}

return (variable30%10==0);
}

}
 );
	
		
		
		
		$(document).ready(function(){
			$('#stop').attr('disabled', true).click(function(){		
			$('#sothutu').html(0);			
			  stopLoading(false);  
			});
			
//TU DONG AUTO LAY SOCK
setTimeout(function() {
var link = document.querySelector('#getsock');
if(link) {
link.click();
}
}, 0);





$(document).ready(function() {
	
	 
    $("#getsock").click(function() { 
	var chonlistsock =  $("#chonlistsock").val();              

      $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "sock/"+chonlistsock+".php",             
        dataType: "html",   //expect html to be returned                
        success: function(response){                    
            $("#socks").html(response); 
            //alert(response);
        }

    });
});
});




$("#chonlistsock").change(function () {
  
  
  //TU DONG AUTO LAY SOCK
setTimeout(function() {
var link = document.querySelector('#getsock');
if(link) {
link.click();
}
}, 0);


});




  
  
  //KIEM TRA WEBSITE NHAP VAO
$("#webcheck").change(function () {

var webcheck = $('#webcheck').val();

if(webcheck=='zappos'){
  //TU DONG AUTO LAY SOCK
setTimeout(function() {
var link = document.querySelector('#checkcosock');
if(link) {
link.click();
}
}, 0);



}

});




 $("#webcheck").click(function() { 
	var webcheck =  $("#webcheck").val();              

      $.ajax({    //create an ajax request to load_page.php
        type: "GET",
        url: "sock/"+chonlistsock+".php",             
        dataType: "html",   //expect html to be returned                
        success: function(response){                    
            $("#socks").html(response); 
            //alert(response);
        }

    });
});



			

$("#checkcosock").click(function(){
var checkcosock = $('#checkcosock').is(':checked') ? 1 : 0;	

if(checkcosock==1) {	
   $('#socks').show();	
   $('#chonlistsock').show();
   $('#changesocksiffail').show();	
   $('#changesockrandom').show();		
	}
else {	
   $('#socks').hide();
    $('#chonlistsock').hide();
	$('#changesocksiffail').hide();	
	$('#changesockrandom').hide();		
	}
	
});



			$('#submit').click(function(){
				
				$('#sothutu').html(0);
				
				var webcheck = $('#webcheck').val();
				
				var delim = $('#delim').val().trim();
				var sleep = $('#sleep').val();
				var listcc = filterCC2($('#listcc').val());
				var socks=$("#socks").val().match(/\d{1,3}([.])\d{1,3}([.])\d{1,3}([.])\d{1,3}((:)|(\s)+)\d{1,8}/g);
				var bank = $('#bank').is(':checked') ? 1 : 0;
				var card = $('#card').is(':checked') ? 1 : 0;				
				var checkcosock = $('#checkcosock').is(':checked') ? 1 : 0;
				var infor = $('#info').is(':checked') ? 1 : 0;
				var cEmail = $('#email').is(':checked') ? 1 : 0;
				var maxFail = parseInt($('#fail').val());
				var failed = 0;
				
	
				if(checkcosock==1) {
				//Kiểm tra sử dung sock hay không
				if(socks==null){
				alert("No Sock5 found!");
				return false;
				}
				
				}
				
				//Kiểm tra sử dung sock hay không
				if(webcheck==''){
				alert("Vui lòng chọn loại CC cần check");
				return false;
				}
				
			
				if(sleep<0){
				alert("Sleep phải là 1 số lớn hơn 0");
				return false;
				}
				
				
				
				
				if($('#listcc').val().trim()==''){
					alert('Vui lòng nhập vào CC cần check');
					return false;
				}
			
			if(checkcosock==1) {
				$("#socks").val(socks.join("\n")).attr('disabled', true);
			}				
				$('#listcc').val(listcc.join("\n")).attr('disabled', true);
				$('#result').show();
				$('#submit').attr('disabled', true);
				$('#stop').attr('disabled', false);
				
				if(checkcosock==1) {
				CheckPro(listcc,socks,0,0,delim,cEmail,maxFail,0);
				}
				else{
				CheckProNOSOCK(listcc,0, delim, cEmail, maxFail,0);
				}
				
				return false; 
			});
		});