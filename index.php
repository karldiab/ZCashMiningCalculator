<!DOCTYPE html>
<html lang="en" ng-app ng-controller="data">
  <head>
    <title>ZCash Mining Calculator</title>
    <meta name="description" content="An easy to use crypto-currency finance utility used to calculate a ZCash miner's potential profits in ETH and multiple fiat
                                currencies. The calculator fetches price and network data from the internet
                                 and only requires the hash rate (speed of mining) from the user. A projected future profit
                                  chart is created dynamically and displayed instantly.">
    <meta name="keywords" content="ZCash,Mining,Profitability,Calculator,AngularJS,AJAX,finance,currency,cryptocurrency,money,bitcoin,Ethereum">
    <meta name="author" content="Karl Diab">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0/angular.min.js"></script>
    <script src="js/app.js"></script>
    <script src="js/Chart.min.js"></script>
    <link rel="stylesheet" href="css/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel='stylesheet' href='../MiningCalcSideBar/style/style.css'>
    <link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-73629036-1', 'auto');
      ga('send', 'pageview');

    </script>
  </head>

<body>
    <?php 
    require_once('../MiningCalcSideBar/echoSideBar.php');
    echoTopHalf();
    ?> 
    <div id="header">
        <div id="bigTitle"><h2>ZCash Mining Calculator</h2></div>
        <div id="smallTitle"><h4>ZCash Mining Calculator</h4></div>
        <div id="infoMessage">
            <p><img src="../images/XMR.png"> Monero calculator added. Check it out!</p>
        </div>  
    </div>
    <div id="desktopAdBanner">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- EthCalcBanner -->
        <ins class="adsbygoogle"
            style="display:inline-block;width:728px;height:90px"
            data-ad-client="ca-pub-7592216756911114"
            data-ad-slot="2667682183"></ins>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
    <div id="mobileAdBanner">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- EthBannerMobile -->
        <ins class="adsbygoogle"
            style="display:inline-block;width:320px;height:100px"
            data-ad-client="ca-pub-7592216756911114"
            data-ad-slot="5773448986"></ins>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
    <div class="row">
        <div class="animated zoomInRight col-md-5">
                <table id="inputTable">
                    <tr>
                        <th>Hashrate:</th>
                        <td>
                            <input type="number" id="userHash" ng-model="userHash" 
                            ng-change="computeProfits()" placeholder="Enter Hashrate" />
                            <select ng-model="userHashSuffix" ng-change="computeProfits()">
                            <option value="s" selected="selected" ng-init="userHashSuffix = 's'">Sols/s</option>
                            <option value="ks">kSols/s</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Difficulty:</th>
                        <td><input type="number" ng-model="difficulty" ng-change="computeProfits()"/></td>
                    </tr>
                    <tr>
                        <th>Block Reward:</th>
                        <td><input type="number" ng-model="blockReward" ng-change="computeProfits()"/> ZEC</td>
                    </tr>
                    <tr>
                        <th>ZEC Price:</th>
                        <td><input type="number" ng-model="price" ng-change="computeProfits()"/>
                            <select ng-model="currency" ng-change="fetchPriceOnly()">
                            <option value="USD" ng-init="currencyCode = 'USD'">USD</option>
                            <option value="RUB">RUB</option>
                            <option value="CNY">CNY</option>
                            <option value="EUR">EUR</option>
                            <option value="CAD">CAD</option>
                            <option value="HKD">HKD</option>
                            <option value="GBP">GBP</option>
                            <option value="JPY">JPY</option>
                            <option value="AUD">AUD</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Power:</th>
                        <td><input type="number" ng-model="wattage" ng-change="computeProfits()" ng-init="wattage = 0"/>
                            <select ng-model="powerSuffix" ng-change="computeProfits()">
                            <option value="W" selected="powerSuffix" ng-init="powerSuffix = 'W'">W</option>
                            <option value="kW">kW</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Power Cost:</th>
                        <td><input type="number" ng-model="powerCost" ng-change="computeProfits()" ng-init="powerCost = 0.1"/> {{ currency }} / kWh</td>
                    </tr>
                    <tr>
                        <th>Pool Fee</th>
                        <td><input type="number" ng-model="poolFee" ng-change="computeProfits()" ng-init="poolFee = 0"/> %</td>
                    </tr>
                    <tr>
                        <th>Diff Change</th>
                        <td><input type="number" ng-model="diffChange" ng-change="computeProfits()"/> / Week</td>
                    </tr>
                </table>
                <h3>Profits At This Difficulty</h3>
            <div>
                <table id="outputTable" cellpadding="10">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>ZEC</th>
                            <th>{{currency}}</th>
                            <th>Power Cost ({{currency}})</th>
                            <th>Pool Fees ({{currency}})</th>
                            <th>Profit ({{currency}})</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Hourly</td>
                            <td>{{values[0][0]|number:4}}</td>
                            <td>{{values[1][0]|currency}}</td>
                            <td>{{values[2][0]|currency}}</td>
                            <td>{{values[3][0]|currency}}</td>
                            <td ng-class="{negative: values[4][0] < 0}">{{values[4][0]|currency}}</td>
                        </tr>
                        <tr>
                            <td>Daily</td>
                            <td>{{values[0][1]|number:4}}</td>
                            <td>{{values[1][1]|currency}}</td>
                            <td>{{values[2][1]|currency}}</td>
                            <td>{{values[3][1]|currency}}</td>
                            <td ng-class="{negative: values[4][1] < 0}">{{values[4][1]|currency}}</td>
                        </tr>
                        <tr>
                            <td>Weekly</td>
                            <td>{{values[0][2]|number:4}}</td>
                            <td>{{values[1][2]|currency}}</td>
                            <td>{{values[2][2]|currency}}</td>
                            <td>{{values[3][2]|currency}}</td>
                            <td ng-class="{negative: values[4][2] < 0}">{{values[4][2]|currency}}</td>
                        </tr>
                        <tr>
                            <td>Monthly</td>
                            <td>{{values[0][3]|number:4}}</td>
                            <td>{{values[1][3]|currency}}</td>
                            <td>{{values[2][3]|currency}}</td>
                            <td>{{values[3][3]|currency}}</td>
                            <td ng-class="{negative: values[4][3] < 0}">{{values[4][3]|currency}}</td>
                        </tr>
                        <tr>
                            <td>Yearly</td>
                            <td>{{values[0][4]|number:3}}</td>
                            <td>{{values[1][4]|currency}}</td>
                            <td>{{values[2][4]|currency}}</td>
                            <td>{{values[3][4]|currency}}</td>
                            <td ng-class="{negative: values[4][4] < 0}">{{values[4][4]|currency}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-5 animated fadeIn" id="chartContainer">
            <div id="chartNotReady" ng-hide="myLineChart">
                <h3>Enter hashrate data for responsive chart!</h3>
            </div>
            <div  ng-show="myLineChart">
            <h3>Estimated Total Future Profits ({{currency}})</h3>
            <canvas id="myChart" height="400px" width="300px"></canvas><br/>
            Time Frame:
            <input type="number" ng-model="timeFrame" id="axisChange" ng-init="timeFrame = 6" ng-change="changeAxis()"/> Months</br>
            </div>
        </div>
        <div class="col-md-2">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- EthereumCalc -->
            <ins class="adsbygoogle"
                style="display:block"
                data-ad-client="ca-pub-7592216756911114"
                data-ad-slot="2807282988"
                data-ad-format="auto"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    </div>
    <div id="notes">
        <h4>Notes</h4>
        <ul>
            <li>Future difficulty prediction factor has been added to the graph! Clear your cache if it's not working for you.</li>
            <li>Remember to take your hashrate from what your pool reports, this eliminates any error from stale shares and miner dev fees (Claymore's miner)</li>
            <li>Do you find this calculator accurate/inaccurate or have a question or comment? Send me an email, link below!</li>
            <li>The utility fetches live ZCash network & price data from https://explorer.zcha.in and http://coinmarketcap.com</li>
    </div>
    <div id="authorInfo">
        <a href="http://www.karldiab.com"><button class="btn btn-success btn-sm">Website</button></a>
        <a href='&#109;&#97;&#105;&#108;&#116;&#111;&#58;&#107;&#97;&#114;&#108;&#64;&#107;&#97;&#114;&#108;&#100;&#105;&#97;&#98;&#46;&#99;&#111;&#109;'><button class="btn btn-primary btn-sm">Email</button></a>
        <a href="https://github.com/karldiab/ZCashMiningCalculator"><button class="btn btn-danger btn-sm">Source Code</button></a>
    </div>
    <?php 
    echoBottomHalf();
    ?>
</body>
</html>



