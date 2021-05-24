var Messenger = function (el) {
  "use strict";
  var m = this;

  m.init = function () {
    m.codeLetters = "&#*+%?£@§$";
    m.message = 0;
    m.current_length = 0;
    m.fadeBuffer = false;
    m.messages = [
      "...",
      "اگه حرفی درباره ی بخش کامپیوتر",
      "از انتقاد به گریدرا و استادا",
      "تا گله و شکایت درباره ی",
      "انتخاب واحد و غیره داری",
      "یا اگه چیزی تو دلت مونده و",
      "نمیخوای به صورت مستقیم بیانش کنی",
      "میتونی پیامتو ",
      "از طریق سایت یا بات بفرستی",
      "تا به صورت ناشناش",
      "داخل کانال قرار بگیره.",
    ];

    setTimeout(m.animateIn, 100);
  };

  m.generateRandomString = function (length) {
    var random_text = "";
    while (random_text.length < length) {
      random_text += m.codeLetters.charAt(
        Math.floor(Math.random() * m.codeLetters.length)
      );
    }

    return random_text;
  };

  m.animateIn = function () {
    if (m.current_length < m.messages[m.message].length) {
      m.current_length = m.current_length + 2;
      if (m.current_length > m.messages[m.message].length) {
        m.current_length = m.messages[m.message].length;
      }

      var message = m.generateRandomString(m.current_length);
      el.innerHTML = message;

      setTimeout(m.animateIn, 20);
    } else {
      setTimeout(m.animateFadeBuffer, 20);
    }
  };

  m.animateFadeBuffer = function () {
    if (m.fadeBuffer === false) {
      m.fadeBuffer = [];
      for (var i = 0; i < m.messages[m.message].length; i++) {
        m.fadeBuffer.push({
          c: Math.floor(Math.random() * 12) + 1,
          l: m.messages[m.message].charAt(i),
        });
      }
    }

    var do_cycles = false;
    var message = "";

    for (var i = 0; i < m.fadeBuffer.length; i++) {
      var fader = m.fadeBuffer[i];
      if (fader.c > 0) {
        do_cycles = true;
        fader.c--;
        message += m.codeLetters.charAt(
          Math.floor(Math.random() * m.codeLetters.length)
        );
      } else {
        message += fader.l;
      }
    }

    el.innerHTML = message;

    if (do_cycles === true) {
      setTimeout(m.animateFadeBuffer, 50);
    } else {
      setTimeout(m.cycleText, 2000);
    }
  };

  m.cycleText = function () {
    m.message = m.message + 1;
    if (m.message >= m.messages.length) {
      m.message = 0;
    }

    m.current_length = 0;
    m.fadeBuffer = false;
    el.innerHTML = "";

    setTimeout(m.animateIn, 200);
  };

  m.init();
};

function showAdd() {
  hideAll();
  document.getElementById("addComment").classList.remove("hide");
}

function goHome() {
  hideAll();
  document.getElementById("entry").classList.remove("hide");
}

function hideAll() {
  document.getElementById("entry").classList.add("hide");
  document.getElementById("allComments").classList.add("hide");
  document.getElementById("addComment").classList.add("hide");
  document.getElementById("login").classList.add("hide");
  document.getElementById("errorMessage").classList.add("hide");
}

const ms = new Messenger(document.getElementById("preview"));

//
const toggle = document.getElementById("toggle-mode");

//at first is dark
// dark = true
// light = false
var mode = localStorage.getItem("mode");
if (mode === "light") {
  document.body.classList.toggle("light");
  localStorage.setItem("mode", "light");
  mode = "light";
  document.getElementById("toggle-mode").checked = false;
} else {
  localStorage.setItem("mode", "dark");
  mode = "dark";
}

toggle.addEventListener("change", () => {
  document.body.classList.toggle("light");
  mode = mode == "dark" ? "light" : "dark";
  localStorage.setItem("mode", mode); //to light
});

function wantToAnswer() {
  document.getElementById("linkContainer").classList.remove("hide");
  document.getElementById("wantToAnswerBtn").classList.add("hide");
}
