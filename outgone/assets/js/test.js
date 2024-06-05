function register(form){
    if(form=="personal"){
        var id = $("#s_no").val();
        var mobile = $("#mobile").val();
        var reg_no = $("#reg_number").val();
        var name = $("#name").val();
        var dob = $("#mobile").val();
        var gender = $("#gender").val();
        var f_name = $("#f_name").val();
        var m_name = $("#m_name").val();
        var whatsapp_number = $("#whatsapp_number").val();
        var email = $("#email").val();
        var address1 = $("#address1").val();
        var address2 = $("#address2").val();
        var district = $("#district").val();
        var state = $("#state").val();
        var pincode = $("#pincode").val();
        var how_known = $("#how_known").val();
        var community = $("#community").val();
        var caste = $("#caste").val();
        
        var arg = 'id='+id+'&mobile='+mobile+'&reg_no='+reg_no+'&name='+name+'&dob='+dob+'&gender='+gender+'&f_name='+f_name+'&m_name='+m_name+'&whatsapp_number='+whatsapp_number+'&email='+email+'&address1='+address1+'&address2='+address2+'&district='+district+'&state='+state+'&pincode='+pincode+'&how_known='+how_known+'&community='+community+'&caste='+caste;

        $.ajax({
            type: 'POST',
            url: 'admission_register.php',
            data: arg+'&form=personal',
            success: function (e) {
                console.log(e);

                var val = e.split("|");

                if(val[0] == 1){
                    alert(val[1]);
                    
                }else{
                    alert(val[1]); 
                }
            }
        });
    }
    else if(form=="educational"){

    }        
}