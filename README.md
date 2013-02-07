twine-storage
=============

Twine storage is a small set of scripts that allows you to regularly pull data from your twine and store it into a MongoDB server.

Put the `www` folder in a php environment.

Edit `lib.php`, and add:

1. Configuration of your MongoDB server
2. The session cookie of your browser when logged in.
3. Map the twine Identifiers of your twines to real names. You MUST add real twine IDs to be able to fetch any data.

You may setup a free MongoDB account at [mongohq.com](https://www.mongohq.com/pricing).

One way of easily find the cookie string, is to login to twine dashboard using chrome, open the web inspector and copy this string:

![](http://clippings.erlang.no/ZZ583045A3.jpg)


Next, setup a cron job that run the `push.php` script every minute.

	* * * * * /usr/bin/curl http://yourserver.no/twine-storage/push.php

The `timeseries.php` scripts is an example JSON feed for 24 hours latest set of temperatures.


Then put up a web page, and use in example [JQuery Flot charts](http://www.flotcharts.org) to plot the temperatures.


	// data is the JSON response.
	// If you pull data from a remote server, ensure you setup CORS correctly.
	// This examples also uses momentjs.com
	function(data) {

		var now = moment();
		var dayfill = parseInt(now.format("H"), 10) + (now.format("m") / 60);

		var start = 6;
		var markings = [
	        { color: '#aaa', lineWidth: 1, xaxis: { from: start, to: dayfill } }
	    ];

	    var chd = [];
	    for(var key in data) {
	    	chd.push(
	    		{
	    			// label: data[key]['name'],
	    			data: data[key]['data'],
	    			color: this.colors[data[key]['name']],
	    			lines: { show: true },
	    			points: { show: true }
	    		});
	    }

		var markings = [
	        { color: '#777', lineWidth: 1, xaxis: { from: moment().sod() - 2*3600*1000, to: moment().sod() + 7*3600*1000 } },
	        { color: '#aaf', lineWidth: 1, yaxis: { from: -10, to: 0 } },
	    ];

		$("div#main").empty().append('<div id="floph" style="width:100%;height:200px;"></div>');
		$.plot($("#floph"), 
			chd, 
			{
				grid: { 
					markings: markings 
				},
				xaxis: { 
					mode: "time"
				}
			}
		);

	}

