const apiCall = fetch('localhost/WEATHER/weather2.php')
  console.log(apiCall);
  // Converting the given API in string into J-SON format
  apiCall.then((response) => {
    return response.json();
})
  
 
    .then((response) => {
    console.log(response);
  localStorage.setItem("Cityname",response.Cityname);
  localStorage.setItem("Pressure",response.Pressure);
  localStorage.setItem("Description",response.Description);
  localStorage.setItem("Temperature",response.Temperature);
  localStorage.setItem("Humidity",response.Humidity);
  localStorage.setItem("WindSpeed",response.WindSpeed);
  localStorage.setItem("WindDegree",response.WindDegree);
  localStorage.setItem("APIDATE",response.APIDATE);
  localStorage.setItem("Icon",response.Icon);
  

  if(`${localStorage.APIDATE}` != null
    && parseInt(localStorage.APIDATE) + 10000 > Date.now()) {
    let freshness = Math.round((Date.now() - `${localStorage.APIDATE}`)/1000) + " second(s)";
    document.getElementById("condition").innerHTML = `${localStorage.Description}`;// attaining the desciption of weather in the id condition
    document.getElementById("tepp").innerHTML = `${localStorage.Temperature}` + " &#176C"; // attaining the amount of temperature in the id tepp
    document.getElementById("tem").innerHTML = `${localStorage.Temperature}` + " &#176C";// attaining the amount of temperature in the id tepp
    document.getElementById("pre").innerHTML = `${localStorage.Pressure}` +" hPa"; // attaining the amount of pressure in the id pre
    document.getElementById("hud").innerHTML = `${localStorage.Humidity}` + " %"; // attaining the value of humidity in the id hud
    document.getElementById("ws").innerHTML = `${localStorage.WindSpeed}` + " m/s"; // attaining the value of wind speed in the id ws
    document.getElementById("wd").innerHTML = `${localStorage.WindDegree}` + "&#176"; // attaining the direction of wind in the id wd
    document.getElementById("icon").src="http://openweathermap.org/img/wn/"+ `${localStorage.Icon}`+"@2x.png" // attaining the respective weather icon in the id icon
    document.getElementById("nam").innerHTML = `${localStorage.Cityname}`; // attaining the location in id nam
    
    } else {


    
    
  document.getElementById("condition").innerHTML = `${localStorage.Description}`// attaining the desciption of weather in the id condition
  document.getElementById("tepp").innerHTML = `${localStorage.Temperature}` + " &#176C"; // attaining the amount of temperature in the id tepp
  document.getElementById("tem").innerHTML = `${localStorage.Temperature}` + " &#176C";// attaining the amount of temperature in the id tepp
  document.getElementById("pre").innerHTML = `${localStorage.Pressure}` +" hPa"; // attaining the amount of pressure in the id pre
  document.getElementById("hud").innerHTML = `${localStorage.Humidity}` + " %"; // attaining the value of humidity in the id hud
  document.getElementById("ws").innerHTML = `${localStorage.WindSpeed}` + " m/s"; // attaining the value of wind speed in the id ws
  document.getElementById("wd").innerHTML = `${localStorage.WindDegree}` + "&#176"; // attaining the direction of wind in the id wd
  document.getElementById("dd").innerHTML = `${localStorage.APIDATE}`  // attaining the value of current time in the id dd
  document.getElementById("icon").src="http://openweathermap.org/img/wn/"+ `${localStorage.Icon}` +"@2x.png" // attaining the respective weather icon in the id icon
  document.getElementById("nam").innerHTML = `${localStorage.Cityname}`; // attaining the location in id nam
}});
  


