var IPI = {
  config: null,
  loadingIndicator: null,
  highlights: [],
  highlightSteps: [],
  highlightStepIndex: 0,
  ipiCall: function ipiCall(url, callback) {
    IPI.loadingIndicator.show();
    $.get(url, {}, function (data) {
      var _result$newAjaxToken;
      IPI.loadingIndicator.hide();
      var result = JSON.parse(data);
      token =
        (_result$newAjaxToken = result.newAjaxToken) !== null &&
        _result$newAjaxToken !== void 0
          ? _result$newAjaxToken
          : token;
      if (typeof result.success === "undefined" || result.success !== true) {
        if (typeof result.error !== "undefined") {
          fadeBox(result.error, true);
        }
        return;
      }
      callback(result);
    });
  },
  trackTask: function trackTask(taskId) {
    var _token;
    IPI.ipiCall(
      IPI.config.trackTaskUrl +
        "&token=" +
        ((_token = token) !== null && _token !== void 0 ? _token : "0") +
        "&taskId=" +
        taskId,
      function (result) {
        var taskElements = document.querySelectorAll(".ipiTaskItem");
        taskElements.forEach(function (taskElement) {
          var _taskElement$getAttri;
          var taskTrackElement = taskElement.querySelector(".ipiTaskItemTrack");
          var currentState =
            (_taskElement$getAttri = taskElement.getAttribute("data-state")) !==
              null && _taskElement$getAttri !== void 0
              ? _taskElement$getAttri
              : "none";
          if (parseInt(taskElement.getAttribute("data-taskid")) === taskId) {
            taskElement.setAttribute(
              "data-state",
              currentState === "none" ? "tracked" : "none",
            );
            taskTrackElement.textContent =
              currentState === "none"
                ? IPI.config.loca.LOCA_IPI_UNTRACK_TASK
                : IPI.config.loca.LOCA_IPI_TRACK_TASK;
            IPI.showTaskDescription(taskElement);
            return;
          }
          if (currentState === "tracked") {
            taskElement.setAttribute("data-state", "none");
            taskTrackElement.textContent = IPI.config.loca.LOCA_IPI_TRACK_TASK;
          }
        });
        if (typeof result.trackedAction !== "undefined") {
          var _result$trackedAction,
            _result$trackedAction2,
            _result$trackedAction3,
            _result$trackedAction4;
          var actionTitle =
            (_result$trackedAction =
              (_result$trackedAction2 = result.trackedAction) === null ||
              _result$trackedAction2 === void 0
                ? void 0
                : _result$trackedAction2.title) !== null &&
            _result$trackedAction !== void 0
              ? _result$trackedAction
              : "";
          var actionHighlights =
            (_result$trackedAction3 =
              (_result$trackedAction4 = result.trackedAction) === null ||
              _result$trackedAction4 === void 0
                ? void 0
                : _result$trackedAction4.highlights) !== null &&
            _result$trackedAction3 !== void 0
              ? _result$trackedAction3
              : [];
          IPI.updateCurrentAction(actionTitle, actionHighlights);
        }
      },
    );
  },
  collectTask: function collectTask(taskId) {
    var _token2;
    IPI.ipiCall(
      IPI.config.collectTaskUrl +
        "&token=" +
        ((_token2 = token) !== null && _token2 !== void 0 ? _token2 : "0") +
        "&taskId=" +
        taskId,
      function (result) {
        var _result$claimedReward;
        IPI.markTaskAsCollected(
          document.querySelector('.ipiTaskItem[data-taskid="' + taskId + '"]'),
        );
        if (typeof result.unclaimedRewards !== "undefined") {
          var collectHint = document.querySelector(
            "#ipiOverviewChapters .ipiChapterItem.active .ipiHintCollect",
          );
          if (parseInt(result.unclaimedRewards) > 0) {
            collectHint.textContent = result.unclaimedRewards;
          } else {
            collectHint.remove();
          }
        }
        getAjaxResourcebox();
        fadeBox(
          (_result$claimedReward = result.claimedRewardsRendered) !== null &&
            _result$claimedReward !== void 0
            ? _result$claimedReward
            : "",
          false,
        );
      },
    );
  },
  collectChapter: function collectChapter(chapterId) {
    var _token3;
    if (chapterId <= 0) {
      console.error("Invalid ChapterId: " + chapterId);
      return;
    }
    IPI.ipiCall(
      IPI.config.collectChapterUrl +
        "&token=" +
        ((_token3 = token) !== null && _token3 !== void 0 ? _token3 : "0") +
        "&chapterId=" +
        chapterId,
      function (result) {
        var _result$claimedReward2;
        var collectChapterElement = document.querySelector(
          "#ipiOverviewChapterRewards .ipiOverviewCollectRewards",
        );
        collectChapterElement.classList.add("disabled");
        collectChapterElement.textContent =
          IPI.config.loca.LOCA_IPI_CHAPTER_COLLECTED;
        document
          .querySelectorAll('.ipiTaskItem:not([data-state="collected"])')
          .forEach(IPI.markTaskAsCollected);
        getAjaxResourcebox();
        fadeBox(
          (_result$claimedReward2 = result.claimedRewardsRendered) !== null &&
            _result$claimedReward2 !== void 0
            ? _result$claimedReward2
            : "",
          false,
        );
      },
    );
  },
  initIpiLayer: function initIpiLayer(config) {
    IPI.config = config;
    IPI.loadingIndicator = $("#ipiOverviewContent").ogameLoadingIndicator();
    document
      .querySelectorAll(".ipiOverviewSelectChapter")
      .forEach(function (linkElement) {
        linkElement.addEventListener("click", function (event) {
          return IPI.loadingIndicator.show();
        });
      });
    var taskItems = document.querySelectorAll(".ipiTaskItem");
    taskItems.forEach(function (taskItem) {
      var _taskItem$getAttribut,
        _taskItem$querySelect,
        _progressElement$getA,
        _progressElement$getA2;
      var taskId = parseInt(
        (_taskItem$getAttribut = taskItem.getAttribute("data-taskid")) !==
          null && _taskItem$getAttribut !== void 0
          ? _taskItem$getAttribut
          : 0,
      );
      (_taskItem$querySelect = taskItem.querySelector(".ipiTaskItemTitle")) ===
        null ||
        _taskItem$querySelect === void 0 ||
        _taskItem$querySelect.addEventListener("click", function (event) {
          if (taskItem.classList.contains("active")) {
            IPI.hideTaskDescription(taskItem);
            return;
          }
          IPI.showTaskDescription(taskItem);
        });
      if (taskItem.getAttribute("data-state") === "tracked") {
        IPI.showTaskDescription(taskItem);
      }
      if (
        taskId === 5001 &&
        taskItem.getAttribute("data-state") !== "collected"
      ) {
        IPI.showTaskDescription(taskItem);
      }
      var trackElement = taskItem.querySelector(".ipiTaskItemTrack");
      trackElement.addEventListener("click", function (event) {
        var _taskItem$getAttribut2;
        if (IPI.config === null) {
          console.error("IPI Config missing");
          return;
        }
        var state =
          (_taskItem$getAttribut2 = taskItem.getAttribute("data-state")) !==
            null && _taskItem$getAttribut2 !== void 0
            ? _taskItem$getAttribut2
            : "none";
        if (taskId <= 0) {
          console.error("Invalid TaskId: " + taskId);
          return;
        }
        switch (state) {
          case "none":
          case "tracked":
            IPI.trackTask(taskId);
            break;
          case "completed":
            IPI.collectTask(taskId);
        }
      });
      var collectElement = taskItem.querySelector(".ipiOverviewCollectRewards");
      collectElement.addEventListener("click", function (event) {
        if (taskItem.getAttribute("data-state") !== "completed") {
          return;
        }
        IPI.collectTask(taskId);
      });
      var progressElement = taskItem.querySelector(".ipiTaskItemProgress");
      var ipiTaskCompletedMark = taskItem.querySelector(
        ".ipiTaskCompletedMark",
      );
      var progress = parseInt(
        (_progressElement$getA =
          progressElement.getAttribute("data-progress")) !== null &&
          _progressElement$getA !== void 0
          ? _progressElement$getA
          : 0,
      );
      var total = parseInt(
        (_progressElement$getA2 =
          progressElement.getAttribute("data-total")) !== null &&
          _progressElement$getA2 !== void 0
          ? _progressElement$getA2
          : 0,
      );
      if (progress > 0 && total > 0 && total >= progress) {
        var progressPercent = (progress / total) * 100;
        var progressColor = "#5a5716";
        var backgroundColor = "transparent";
        if (progressPercent >= 100) {
          progressColor = "#4f4f4f";
        }
        if (progressPercent < 100) {
          ipiTaskCompletedMark.style.display = "none";
        }
        progressElement.style.background = "linear-gradient(90deg, "
          .concat(progressColor, " ")
          .concat(progressPercent, "%, ")
          .concat(backgroundColor, " ")
          .concat(progressPercent, "%)");
      }
      var ipiMenuWrapper = document.querySelector("#ipiMenuWrapper");
      if (ipiMenuWrapper !== null) {
        changeTooltip(ipiMenuWrapper, "");
      }
    });
    var collectChapterElement = document.querySelector(
      "#ipiOverviewChapterRewards .ipiOverviewCollectRewards",
    );
    collectChapterElement.addEventListener("click", function (event) {
      var _collectChapterElemen;
      if (IPI.config === null) {
        console.error("IPI Config missing");
        return;
      }
      if (collectChapterElement.classList.contains("disabled")) {
        return;
      }
      var target = parseInt(
        (_collectChapterElemen =
          collectChapterElement.getAttribute("data-target")) !== null &&
          _collectChapterElemen !== void 0
          ? _collectChapterElemen
          : 0,
      );
      if (target <= 0) {
        console.error("Invalid ChapterId: " + target);
        return;
      }
      IPI.collectChapter(target);
    });
  },
  showTaskDescription: function showTaskDescription(taskItem) {
    taskItem.classList.add("active");
    var contentElement = taskItem.querySelector(".ipiTaskItemContent");
    $(contentElement).slideDown();
  },
  hideTaskDescription: function hideTaskDescription(taskItem) {
    taskItem.classList.remove("active");
    var contentElement = taskItem.querySelector(".ipiTaskItemContent");
    $(contentElement).slideUp();
  },
  markTaskAsCollected: function markTaskAsCollected(taskElement) {
    var taskTrackElement = taskElement.querySelector(".ipiTaskItemTrack");
    taskElement.setAttribute("data-state", "collected");
    taskTrackElement.textContent = IPI.config.loca.LOCA_IPI_TASK_COLLECTED;
    var claimTaskRewardsButton = taskElement.querySelector(
      ".ipiTaskItemContentCollect .claimTaskRewards",
    );
    claimTaskRewardsButton.textContent =
      IPI.config.loca.LOCA_IPI_TASK_COLLECTED;
    claimTaskRewardsButton.classList.add("disabled");
  },
  addHighlight: function addHighlight(highlightName) {
    if (_typeof(highlightName) === "object" && highlightName.length > 0) {
      IPI.highlightSteps = highlightName;
      IPI.highlightStepIndex = 0;
      return IPI.addHighlight(highlightName[IPI.highlightStepIndex]);
    }
    var targets = document.querySelectorAll(
      '.ipiHintable[data-ipi-hint="' + highlightName + '"]',
    );
    if (targets.length <= 0) {
      return false;
    }
    targets.forEach(function (target) {
      target.classList.add("ipiHintActive");
    });
    return true;
  },
  nextHighlightStep: function nextHighlightStep(event) {
    if (
      IPI.highlightSteps.length <= 0 ||
      IPI.highlightStepIndex >= IPI.highlightSteps.length
    ) {
      return;
    }
    var step = event.target.getAttribute("data-ipi-highlight-step");
    var previousHighlights = document.querySelectorAll(
      '.ipiHintActive[data-ipi-hint="' + step + '"]',
    );
    if (previousHighlights.length <= 0) {
      return;
    }
    previousHighlights.forEach(function (target) {
      target.classList.remove("ipiHintActive");
    });
    IPI.highlightStepIndex += 1;
    IPI.addHighlight(IPI.highlightSteps[IPI.highlightStepIndex]);
  },
  initIpiHighlights: function initIpiHighlights(highlights) {
    IPI.highlights = highlights;
    IPI.refreshHighlights();
    document
      .querySelectorAll("[data-ipi-highlight-step]")
      .forEach(function (target) {
        if (target.nodeName.toLowerCase() === "input") {
          target.addEventListener("change", IPI.nextHighlightStep);
          return;
        }
        target.addEventListener("click", IPI.nextHighlightStep);
      });
  },
  refreshHighlights: function refreshHighlights() {
    document
      .querySelectorAll(".ipiHintable.ipiHintActive")
      .forEach(function (target) {
        target.classList.remove("ipiHintActive");
      });
    //this approach is more friendly to old browsers, since they do not support Array.toReversed yet
    var tmpHighlights = Array.from(IPI.highlights).reverse();
    var _iterator = _createForOfIteratorHelper(tmpHighlights),
      _step;
    try {
      var _loop = function _loop() {
          highlight = _step.value;
          if (
            (typeof highlight === "undefined"
              ? "undefined"
              : _typeof(highlight)) === "object"
          ) {
            var highlightAdded = false;
            highlight.forEach(function (sub) {
              return (highlightAdded = IPI.addHighlight(sub) || highlightAdded);
            });
            if (highlightAdded === true) {
              return 0; // break
            }
            return 1; // continue
          }
          if (IPI.addHighlight(highlight) === true) {
            return 0; // break
          }
        },
        _ret;
      for (_iterator.s(); !(_step = _iterator.n()).done; ) {
        _ret = _loop();
        if (_ret === 0) break;
        if (_ret === 1) continue;
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
  },
  updateCurrentAction: function updateCurrentAction(title, highlights) {
    var currentTaskComponent = document.querySelector(
      "#ipimenucomponent .ipiMenuBody",
    );
    var currentTaskFooter = document.querySelector(
      "#ipimenucomponent .ipiMenuFooter",
    );
    currentTaskComponent.textContent = title;
    if (title === "") {
      currentTaskComponent.classList.add("hidden");
      currentTaskFooter.classList.add("hidden");
    } else {
      currentTaskComponent.classList.remove("hidden");
      currentTaskFooter.classList.remove("hidden");
    }
    IPI.highlights = highlights;
    IPI.refreshHighlights();
  },
  initializeMenuContent: function initializeMenuContent(ipiMenuConfig) {
    $.ajax({
      url: ipiMenuConfig.ipiMenuContentUrl,
    })
      .done(function (response) {
        IPI.populateMenuContent(response);
      })
      .fail(function () {
        fadeBox(result.error, true);
        return false;
      });
    $(".ipiMenuBody").mouseover();
    IPI.initIpiHighlights(ipiMenuConfig.highlights);
  },
  populateMenuContent: function populateMenuContent(content) {
    $("#ipiMenuWrapper").html(content);
  },
  updateMenuContent: function updateMenuContent(content) {
    if (typeof content.unclaimedRewards !== "undefined") {
      var collectHint = $("#ipimenucomponent .ipiHintCollect");
      if (parseInt(content.unclaimedRewards) > 0) {
        collectHint.textContent = content.unclaimedRewards;
      } else {
        collectHint.remove();
      }
    }
    if (typeof content.trackedAction !== "undefined") {
      var _content$trackedActio,
        _content$trackedActio2,
        _content$trackedActio3,
        _content$trackedActio4;
      var actionTitle =
        (_content$trackedActio =
          (_content$trackedActio2 = content.trackedAction) === null ||
          _content$trackedActio2 === void 0
            ? void 0
            : _content$trackedActio2.title) !== null &&
        _content$trackedActio !== void 0
          ? _content$trackedActio
          : "";
      var actionHighlights =
        (_content$trackedActio3 =
          (_content$trackedActio4 = content.trackedAction) === null ||
          _content$trackedActio4 === void 0
            ? void 0
            : _content$trackedActio4.highlights) !== null &&
        _content$trackedActio3 !== void 0
          ? _content$trackedActio3
          : [];
      IPI.updateCurrentAction(actionTitle, actionHighlights);
    }
  },
};
function _createForOfIteratorHelper(r, e) {
  var t =
    ("undefined" != typeof Symbol && r[Symbol.iterator]) || r["@@iterator"];
  if (!t) {
    if (
      Array.isArray(r) ||
      (t = _unsupportedIterableToArray(r)) ||
      (e && r && "number" == typeof r.length)
    ) {
      t && (r = t);
      var _n = 0,
        F = function F() {};
      return {
        s: F,
        n: function n() {
          return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] };
        },
        e: function e(r) {
          throw r;
        },
        f: F,
      };
    }
    throw new TypeError(
      "Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.",
    );
  }
  var o,
    a = !0,
    u = !1;
  return {
    s: function s() {
      t = t.call(r);
    },
    n: function n() {
      var r = t.next();
      return ((a = r.done), r);
    },
    e: function e(r) {
      ((u = !0), (o = r));
    },
    f: function f() {
      try {
        a || null == t["return"] || t["return"]();
      } finally {
        if (u) throw o;
      }
    },
  };
}

function getTooltipSelector(selector) {
  var standardSelector =
    ".tooltipPremium, .tooltip, .tooltipRight, .tooltipLeft, .tooltipBottom, .tooltipClose, .tooltipHTML, .tooltipRel, .tooltipAJAX, .tooltipCustom, .markItUpButton a";
  if (typeof selector == "undefined") {
    selector = standardSelector;
  } else if (typeof selector == "string" && !selector.match(/\.tooltip/)) {
    var standardSelectorArray = standardSelector.split(", ");
    var previousSelector = selector;
    for (i in standardSelectorArray) {
      selector += ", " + previousSelector + " " + standardSelectorArray[i];
    }
  }
  return selector;
}
function initTooltips(selector) {
  if (!selector) {
    selector = getTooltipSelector();
  }
  $(selector).each(function (index, object) {
    var content = null;
    if ($(object).hasClass("tooltipRel")) {
      var rel = $(object).attr("rel");
      if (!rel) {
        return;
      }
      var tooltipRelationHtml = $("#".concat(rel));
      if (!tooltipRelationHtml.length) {
        return;
      }
      var tooltipHtml = document.getElementById(rel).cloneNode(true);
      if (!tooltipHtml) {
        return;
      }
      if ($(object).data("tooltip-add-class")) {
        tooltipHtml.classList.add($(object).data("tooltip-add-class"));
      }
      if (tooltipHtml.style.display === "none") {
        tooltipHtml.style.display = "";
      }
      content = tooltipHtml.outerHTML;
      $(object).removeAttr("title");
    } else {
      if (!object.title) {
        return;
      }
      var originalTitle = object.title;
      if ($(object).hasClass("tooltipHTML")) {
        content = generateHTML(sanitizeTooltip(originalTitle));
      } else {
        content = sanitizeTooltip(originalTitle);
      }
      $(object).removeAttr("title");
      $(object).attr("data-tooltip-title", originalTitle);
    }
    if (object._tippy) {
      object._tippy.destroy();
    }
    tippy(object, {
      content: content,
      allowHTML: true,
      placement: getTooltipPlacement(object),
      interactive: getTooltipInteractiveness(object),
      appendTo: appendTooltipTo(object),
      maxWidth: getMaxWidthForTooltip(object),
      zIndex: 1000001,
    });
  });
  function appendTooltipTo(object) {
    var idSelectorToAppendTo = $(object).data("tooltip-append-to-id");
    if (idSelectorToAppendTo) {
      var domObject = document.getElementById(idSelectorToAppendTo);
      if (domObject) {
        return domObject;
      }
    }
    return document.body;
  }
  function getMaxWidthForTooltip(object) {
    if ($(object).data("tooltip-maxwidth")) {
      return parseInt($(object).data("tooltip-maxwidth"));
    }
    return 400;
  }
  function getTooltipPlacement(object) {
    var target = $(object);
    if (target.data("tooltip-placement")) {
      if (
        ["top", "bottom", "right", "left"].indexOf(
          target.data("tooltip-placement"),
        ) > -1
      ) {
        return target.data("tooltip-placement");
      }
      return "top";
    }
    switch (true) {
      case target.hasClass("tooltipBottom"):
        return "bottom";
      case target.hasClass("tooltipRight"):
      case target.hasClass("tooltipRel"):
        return "right";
      case target.hasClass("tooltipLeft"):
        return "left";
      default:
        return "top";
    }
  }
  function getTooltipInteractiveness(object) {
    var target = $(object);
    if (target.data("tooltip-interactive")) {
      return true;
    }
    return false;
  }
  function sanitizeTooltip(text) {
    return text.replace(/<\s*script/g, "&lt;script");
  }
  function generateHTML(text) {
    var generatedHTML = $(document.createElement("div")).addClass(
      "htmlTooltip",
    );
    var textClusters = text.split("|");
    var title = $(document.createElement("h1")).html(textClusters.shift());
    generatedHTML.append(title);
    var splitLine = $(document.createElement("div")).addClass("splitLine");
    textClusters.map(function (text, index) {
      generatedHTML.append(splitLine);
      if (index % 2 === 0) {
        generatedHTML.append(text + "</br>");
      } else {
        var newTitle = $(document.createElement("h1")).html(text);
        generatedHTML.append(newTitle);
      }
    });
    return generatedHTML[0];
  }
}
tippy.setDefaultProps({
  delay: [150, null],
});
$(function () {
  initTooltips();
});
function removeTooltip(object) {
  var targetElement = $(object);
  if (targetElement._tippy) {
    targetElement._tippy.destroy();
  }
}



function initOverlayName() {
  var title = $('#technologytree').data('title');
  $("#technologytree").closest(".ui-dialog").find('.ui-dialog-title').html(title);
}
function initTechtree(id) {
  (function ($) {
    drawArrows(id);
  })(jQuery);
  tabletToggleTechtreeInfos(id);
}
