function formatTimeWrapper(timestamp, maxDigits, showUnits, delimiter, zerofill, approx) {
  var timeUnits = {
    week: 604800,
    day: 86400,
    hour: 3600,
    minute: 60,
    second: 1
  };
  var timeString = "";
  for (var k in timeUnits) {
    var nv = Math.floor(timestamp / timeUnits[k]);
    if (maxDigits > 0 && (nv > 0 || zerofill && timeString !== "")) {
      timestamp = timestamp - nv * timeUnits[k];
      if (timeString !== "") {
        timeString += delimiter;
        if (nv < 10 && nv > 0 && zerofill) {
          nv = "0" + nv;
        }
        if (nv === 0) {
          nv = "00";
        }
      }
      timeString += nv + (showUnits ? LocalizationStrings.timeunits['short'][k] : '');
      maxDigits--;
    }
  }
  if (timestamp > 0) {
    timeString = approx + timeString;
  }
  return timeString;
}

function getLeftoverTime(startLeftoverTime, startTime) {
    // attention au fuseau horaire
      var currTime = new Date();
      return Math.round(startLeftoverTime + (currTime.getTime() - startTime) * -1 / 1000);
    }


function updateCountdown(timeLeftInSeconds, timePerUnit, shipCount, targetTechnologyId) {
    let timeLeftInSeconds = timeLeftInSeconds;
    let currentShipCount = parseInt($("li.technology[data-technology=".concat(this.targetTechnologyId, "] span.amount")).attr('data-value'));
    let factor = Math.max(0, timeLeftInSeconds) / timePerUnit;

    // let maxDigits = arguments.length > 7 && arguments[7] !== undefined ? arguments[7] : 2; // retournera tjs 2
    let maxDigits = 2; // retournera tjs 2
    

    let getCurrentTimestring = function () {
        return formatTimeWrapper(thisObj.getLeftoverTime(), maxDigits, thisObj.showunits, thisObj.delimiter, thisObj.zerofill, thisObj.approx);
      };
      $("time.".concat(this.targetName)).text(getCurrentTimestring);
      let factorBar = Math.max(0, (timePerUnit * (shipCount - 1) + this.getLeftoverTime()) / this.totalLeftoverTimeValue);
      if (factor > 0) {
        $("li.technology[data-technology=".concat(targetTechnologyId, "][data-status=\"active\"] .cooldownBackground")).css('height', factorBar * 100 + "%");
      } else {
        // let targetAmount = $(`li.technology[data-technology=${this.targetTechnologyId}] span.targetamount`).data('value')
        if (shipCount > 0) {
          this.shipCount--;
          // targetAmount--
          currentShipCount++;
        }
        if (this.shipCount >= 0) {
          // $(`li.technology[data-technology=${this.targetTechnologyId}] span.targetamount`).attr('data-value', targetAmount).text(targetAmount)
          $(".shipSumCount.".concat(this.targetName)).text(this.shipCount);
        }
        $("li.technology[data-technology=".concat(this.targetTechnologyId, "] span.amount")).attr('data-value', currentShipCount);
        $("li.technology[data-technology=".concat(this.targetTechnologyId, "] span.amount .stockAmount")).text(gfNumberGetHumanReadable(currentShipCount));
        if (shipCount > 0) {
          this.startTime = new Date().getTime();
          this.startLeftoverTime = timePerUnit;
          // new CountdownTimerUnit(this.targetName, this.leftoverTime, this.shipCount, this.targetTechnologyId, this.timePerUnit, this.reloadPage)
          $("time.".concat(this.targetName)).text(LocalizationStrings.status.ready);
        } else {
          if (timeLeftInSeconds <= -1 && timeLeftInSeconds > -6) {
            if (this.reloadPage != null && !isOverlayOpen()) {
                // recharger la page après avoir appelé la route qui augmente le niveau ou ajoute une entité construite
            //   reload_page(this.reloadPage);
            }
            timerHandler.removeCallback(this.timer);
          }
          $("time.".concat(this.targetName)).text(LocalizationStrings.status.ready);
        }
      }
    }

    var CountdownTimerUnit = /*#__PURE__*/function () {
  function CountdownTimerUnit(targetName, leftoverTime, totalTime, shipCount, targetTechnologyId, timePerUnit, reloadPage) {
    var maxDigits = arguments.length > 7 && arguments[7] !== undefined ? arguments[7] : 2;
    _classCallCheck(this, CountdownTimerUnit);
    // default config
    this.timestamp = 0;
    this.maxDigits = parseInt(maxDigits); // with 2 seconds won't be show when time is > 1h
    this.delimiter = " ";
    this.approx = "";
    this.showunits = true;
    this.zerofill = false;
    this.startTime = new Date().getTime();
    this.startLeftoverTime = parseInt(leftoverTime);
    this.totalLeftoverTimeValue = parseInt(totalTime);
    this.shipCount = shipCount;
    this.timePerUnit = timePerUnit;
    this.targetTechnologyId = targetTechnologyId;
    this.targetName = targetName;
    this.reloadPage = reloadPage;
    this.timer = timerHandler.appendCallback(this.updateCountdown.bind(this));
    this.updateCountdown();
  }

  // Getter
  return _createClass(CountdownTimerUnit, [{
    key: "getCurrentTimestring",
    get: function get() {
      return formatTimeWrapper(this.getLeftoverTime(), this.maxDigits, this.showunits, this.delimiter, this.zerofill, this.approx);
    }

    // Method
  }, {
    key: "getLeftoverTime",
    value: function getLeftoverTime() {
      var currTime = new Date();
      return Math.round(this.startLeftoverTime + (currTime.getTime() - this.startTime) * -1 / 1000);
    }
  }, {
    key: "updateCountdown",
    value: function updateCountdown() {
      var timeLeftInSeconds = this.getLeftoverTime();
      var currentShipCount = parseInt($("li.technology[data-technology=".concat(this.targetTechnologyId, "] span.amount")).attr('data-value'));
      var factor = Math.max(0, timeLeftInSeconds) / this.timePerUnit;
      $("time.".concat(this.targetName)).text(this.getCurrentTimestring);
      var factorBar = Math.max(0, (this.timePerUnit * (this.shipCount - 1) + this.getLeftoverTime()) / this.totalLeftoverTimeValue);
      if (factor > 0) {
        $("li.technology[data-technology=".concat(this.targetTechnologyId, "][data-status=\"active\"] .cooldownBackground")).css('height', factorBar * 100 + "%");
      } else {
        // let targetAmount = $(`li.technology[data-technology=${this.targetTechnologyId}] span.targetamount`).data('value')
        if (this.shipCount > 0) {
          this.shipCount--;
          // targetAmount--
          currentShipCount++;
        }
        if (this.shipCount >= 0) {
          // $(`li.technology[data-technology=${this.targetTechnologyId}] span.targetamount`).attr('data-value', targetAmount).text(targetAmount)
          $(".shipSumCount.".concat(this.targetName)).text(this.shipCount);
        }
        $("li.technology[data-technology=".concat(this.targetTechnologyId, "] span.amount")).attr('data-value', currentShipCount);
        $("li.technology[data-technology=".concat(this.targetTechnologyId, "] span.amount .stockAmount")).text(gfNumberGetHumanReadable(currentShipCount));
        if (this.shipCount > 0) {
          this.startTime = new Date().getTime();
          this.startLeftoverTime = this.timePerUnit;
          // new CountdownTimerUnit(this.targetName, this.leftoverTime, this.shipCount, this.targetTechnologyId, this.timePerUnit, this.reloadPage)
          $("time.".concat(this.targetName)).text(LocalizationStrings.status.ready);
        } else {
          if (timeLeftInSeconds <= -1 && timeLeftInSeconds > -6) {
            if (this.reloadPage != null && !isOverlayOpen()) {
              reload_page(this.reloadPage);
            }
            timerHandler.removeCallback(this.timer);
          }
          $("time.".concat(this.targetName)).text(LocalizationStrings.status.ready);
        }
      }
    }
  }]);
}();