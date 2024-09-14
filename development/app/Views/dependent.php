<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
   <link rel="stylesheet" href="/Development/development/public/asstes/style.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
    <section class="spacer">
        <div class="container">
            <p class="fs-7 text-content py-3">
                Dynamic Dependent dropdown 
                <a href="#code8" class="ms-2 text-danger code-btn8"><i class="fa fa-code"></i> </a>
            </p>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Action Cards start-->
                    <div class="card shadow-out mb-5">
                        <div class="card-body ps-5">
                                                        <!--
                        <div class="form-group">
                                <select name="" class="form-control input-lg" onchange="fetchStateData(this.value)">
                                
                                    <option value="">Select Country</option>
                                    <?php foreach ($country as $row){?>
                                        <option value="<?php echo $row->country_id ?>"><?php echo $row->country_name ?></option>
                                    <?php }?>
                                </select>
                            </div>

                                    -->

                            <div class="form-group">
                                <select name="" class="form-control input-lg" id="country_id" onchange="fetchStateData(this.value);">
                                    <option value="">Select Country</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="" class="form-control input-lg" id="state_id" onchange="fetchCityData(this.value)">
                                    <option value="">Select State/Province</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="" class="form-control input-lg" id="city_id">
                                    <option value="">Select City</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    
<script>
    window.onload = fetchCountryData;

    function fetchCountryData(){
        $.ajax({
            url:"<?php echo base_url('Development/development/public/countries')?>",
            method:"POST",
            success:function(result){
                let data = JSON.parse(result);
                document.querySelector("#country_id").innerHTML = data;
                console.log(result);
            }
        });
    }

    function fetchStateData(country_id){
        $.ajax({
            url:"<?php echo base_url('Development/development/public/states')?>",
            method:"POST",
            data:{
                cId:country_id
            },
            success:function(result){
                let data = JSON.parse(result);
                document.querySelector("#state_id").innerHTML = data;
                console.log(result);
            }
        });
    }

    function fetchCityData(state_id){
        $.ajax({
            url:"<?php echo base_url('Development/development/public/cities')?>",
            method:"POST",
            data:{
                sId:state_id
            },
            success:function(result){
                let data = JSON.parse(result);
                document.querySelector("#city_id").innerHTML = data;
                console.log(result);
            }
        });
    }
</script>    
<!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> -->

</body>
</html>