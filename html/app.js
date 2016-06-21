
function app() {
  $(".details,.result").hide();

  var report="";
  var family="";
  var checklist="";
  var species="";

  function get_checklists(){
    $.get('checklists',function(data){
      var result=JSON.parse(data);
      for(var i=0;i<result.length;i++) {
        var option = document.createElement("option");
        option.value=result[i];
        option.innerHTML=result[i].replace(/_/g,' ').toUpperCase();
        document.querySelector('.checklist select').appendChild(option);
      }
    });
  }

  var reports={};
  $.get('reports',function(data){
    var result = JSON.parse(data);
    for(var i=0;i<result.length;i++) {
      var report = result[i];
      reports[report.name]=report;

      var li = document.createElement('li');

      var check = document.createElement('input');
      check.type='radio';
      check.name='report';
      check.value=report.name;
      check.onchange=function(e){ set_report(e.target.value); }

      var label = document.createElement('label');
      label.setAttribute('for','report')

      var title = document.createElement('span');
      title.innerHTML = report.title;

      label.appendChild(check);
      label.appendChild(title);

      li.appendChild(label);

      document.querySelector(".reports ul").appendChild(li);
    }

  });

  document.querySelector('.checklist select').onchange=function(e) {
    set_checklist(e.target.selectedOptions[0].value);
  }

  function set_report(name) {
    $('.result').hide();
    report=reports[name];
    family="";
    species="";
    checklist=""

    $(".checklist select").html("<option value=''></option>");
    get_checklists();
    $(".family select").html("<option value=''>Todos</option>");
    $(".species select").html("<option value=''>Todos</option>");

    $(".details").show();

    document.querySelector('.details .title span').innerHTML=report.title;
    document.querySelector('.details .description span').innerHTML=report.description;
    document.querySelector('.details .fields span').innerHTML=report.fields.join(" ; ");

    $(".checklist,.family,.species").hide();
    if(typeof report.filters == 'object'){
      for(var i=0;i<report.filters.length;i++){
        $("."+report.filters[i]).show();
      }
    }

  }

  function set_checklist(check){
    $('.result').hide();
    $(".family select").html("<option value=''>Todos</option>");
    $(".species select").html("<option value=''>Todos</option>");

    checklist=check;
    family="";
    species="";
    if(checklist =="") { return; }

    $.get('checklist/'+checklist+'/families',function(data){
      var result=JSON.parse(data);
      for(var i=0;i<result.length;i++) {
        var option = document.createElement("option");
        option.value=result[i].toUpperCase();
        option.innerHTML=result[i].toUpperCase();
        document.querySelector('.family select').appendChild(option);
      }
    });
  }

  document.querySelector('.family select').onchange=function(e){
    set_family(e.target.selectedOptions[0].value) ;
  }

  function set_family(f) {
    $('.result').hide();
    family=f;
    species="";
    if(family=='') return;
    $(".species select").html("<option value=''>Todos</option>");

    $.get('checklist/'+checklist+'/family/'+family+'/species',function(data){
      var result=JSON.parse(data);
      for(var i=0;i<result.length;i++) {
        var option = document.createElement("option");
        option.value=result[i].scientificNameWithoutAuthorship;
        option.innerHTML=result[i].scientificName;
        document.querySelector('.species select').appendChild(option);
      }
    });
  }

  document.querySelector('.species select').onchange=function(e){
    $('.result').hide();
    species=e.target.selectedOptions[0].value;
  }

  document.querySelector('form').onsubmit=function(){
    var url = 'generate/'+report.name;
    if(checklist.length>=1){
      url += '/'+checklist;
    } else {
      alert('Selecione um recorte.');
      return false;
    }
    if(family.length>=1){
      url += '/'+family;
    }
    if(species.length>=1){
      url += '/'+species;
    }

    $('.result').show();
    $(".result").html("Gerando...");
    $.get(url,function(data){
        var r = JSON.parse(data);
        if(r.ok) {
          $(".result").html('<a href="'+r.url+'" class="pure-button">Download</a>');
        } else {
          $(".result").html('<span class="error"><strong>Erro</strong>: '+r.error+'</span>');
        }
    });
    return false;
  }
}

window.onload=app;
