"use strict";
function checkAndChangeCellColor() {
  const dateCells = document.querySelectorAll("td.date-cell");

  dateCells.forEach(function (cell) {
    const cellDate = new Date(cell.textContent);
    const currentDate = new Date();
    currentDate.setHours(0, 0, 0, 0);

    if (cellDate.getTime() < currentDate.getTime()) {
      cell.style.color = "white";
      cell.style.backgroundColor = "red";
    } else {
      cell.style.color = ""; // Reset to default color
      cell.style.backgroundColor = ""; // Reset to default background color
    }
  });
}
function setLocalStorage(item, content) {
  localStorage.setItem(item, JSON.stringify(content));
}
function getLocalStorage(item) {
  const data = JSON.parse(localStorage.getItem(item));

  if (!data) return;

  return data;
}
const dateCells = document.querySelectorAll("td.date-cell");
if (dateCells) checkAndChangeCellColor();
function changeCheckboxValue(checkbox) {
  if (checkbox.checked) {
    checkbox.value = "true";
    console.log(checkbox.nextElementSibling);
    // Wyłącz ukryte pole, jeśli checkbox jest zaznaczony
    checkbox.nextElementSibling.disabled = true;
  } else {
    checkbox.value = "false";
    // Włącz ukryte pole, jeśli checkbox nie jest zaznaczony
    checkbox.nextElementSibling.disabled = false;
  }
}

$(function () {
  const minYear = 2023; // minimalny rok do wyświetlenia
  const maxYear = new Date().getFullYear() + 1; // aktualny rok

  // Generowanie opcji dla rozwijanej listy miesięcy
  const polishMonths = [
    "Styczeń",
    "Luty",
    "Marzec",
    "Kwiecień",
    "Maj",
    "Czerwiec",
    "Lipiec",
    "Sierpień",
    "Wrzesień",
    "Październik",
    "Listopad",
    "Grudzień",
  ];

  // Funkcja do generowania opcji dla rozwijanej listy
  function generateOptions(selector, miesiac) {
    const currentMonth = getLocalStorage(miesiac);
    for (let i = 1; i <= 12; i++) {
      const option = $("<option>", {
        value: i,
        text: polishMonths[i - 1],
      });

      if (i == currentMonth) {
        option.prop("selected", true);
      }

      $(selector).append(option);
    }
  }

  // Generowanie opcji dla wszystkich rozwijanych list
  generateOptions("#monthSelect", "month1");
  generateOptions("#monthSelect2", "month2");
  generateOptions("#monthSelect3", "month3");

  // Generowanie opcji dla rozwijanej listy lat
  const currentYear1 = getLocalStorage("year1");
  const currentYear2 = getLocalStorage("year2");
  const currentYear3 = getLocalStorage("year3");
  for (let y = minYear; y <= maxYear; y++) {
    const option = $("<option>", {
      value: y,
      text: y,
    });

    if (y == currentYear1) {
      option.prop("selected", true);
    }

    $("#yearSelect").append(option);
  }
  for (let y = minYear; y <= maxYear; y++) {
    const option = $("<option>", {
      value: y,
      text: y,
    });

    if (y == currentYear2) {
      option.prop("selected", true);
    }

    $("#yearSelect2").append(option);
  }
  for (let y = minYear; y <= maxYear; y++) {
    const option = $("<option>", {
      value: y,
      text: y,
    });

    if (y == currentYear3) {
      option.prop("selected", true);
    }

    $("#yearSelect3").append(option);
  }

  // Obsługa zmiany wartości rozwijanych list
  $("#monthSelect, #yearSelect").on("change", function () {
    const month = $("#monthSelect").val();
    setLocalStorage("month1", month);
    console.log(month);
    const year = $("#yearSelect").val();
    setLocalStorage("year1", year);
    console.log(year);
    const selectedDate = new Date(year, month - 1, 1);
    const formattedDate = $.datepicker.formatDate("yy-mm", selectedDate);
  });
  $("#monthSelect2, #yearSelect2").on("change", function () {
    const month = $("#monthSelect2").val();
    setLocalStorage("month2", month);

    console.log(month);
    const year = $("#yearSelect2").val();
    setLocalStorage("year2", year);

    console.log(year);
    const selectedDate = new Date(year, month - 1, 1);
    const formattedDate = $.datepicker.formatDate("yy-mm", selectedDate);
  });
  $("#monthSelect3, #yearSelect3").on("change", function () {
    const month = $("#monthSelect3").val();
    setLocalStorage("month3", month);

    console.log(month);
    const year = $("#yearSelect3").val();
    setLocalStorage("year3", year);

    console.log(year);
    const selectedDate = new Date(year, month - 1, 1);
    const formattedDate = $.datepicker.formatDate("yy-mm", selectedDate);
  });
});
const odblokuj = document.querySelectorAll(".odblokuj");
const kontakt_deleter = document.querySelectorAll(".kontakt_deleter");
const drukowanie = document.querySelectorAll(".drukowanie");
const firma_option = document.querySelectorAll(".firma_option");
const line_deleting = document.querySelectorAll(".line_deleting");
const kookie = document.cookie.split(";");
const clonning = document.querySelectorAll(".clonning");
const tarch = document.querySelectorAll(".tarch");
const tr = document.querySelectorAll("tr");
const uwagi = document.querySelectorAll(".uwagi");
const klasa = kookie[0].split("=");
const new_line = document.querySelector(".new_line");
const firma_editor = document.querySelector(".firma_editor");
const firma_select = document.querySelector("#firma_select");
const braki_only = document.querySelector(".braki_only");
const nowy_kontakt = document.querySelector(".nowy_kontakt");
const blokada_check = document.querySelectorAll(".blokada_check");
firma_editor.addEventListener("input", (e) => {
  firma_select.dispatchEvent(new Event("change"));
});
if (odblokuj) {
  odblokuj.forEach((el) =>
    el.addEventListener("click", (e) => {
      e.target.previousSibling.removeAttribute("disabled");
      e.target.previousSibling.classList.remove("zablokowany");
    })
  );
}

if (document.querySelector("." + klasa[1]))
  document.querySelector("." + klasa[1]).style.display = "flex";

const nav = document.querySelector("nav");
const nav_buttons = document.querySelectorAll(".nav");
class App {
  constructor() {
    if (clonning) {
      clonning.forEach((el) => {
        el.addEventListener("click", this.handleCloning.bind(this));
      });
    }
    if (kontakt_deleter)
      kontakt_deleter.forEach((el) => {
        el.addEventListener("click", (e) => {
          e.target.closest(".closest").remove();
        });
      });
    if (nowy_kontakt)
      nowy_kontakt.addEventListener("click", this.dodaj_nowy_kontakt);
    if (braki_only) braki_only.addEventListener("click", this.braki_w_km);
    if (drukowanie) {
      drukowanie.forEach((el) => el.addEventListener("click", this.print));
    }
    if (new_line) new_line.addEventListener("click", this.enter_new_line);
    line_deleting.forEach((el) =>
      el.addEventListener("click", this.delete_a_line)
    );
    if (firma_option) {
      firma_editor.addEventListener("change", this.reduce_options);
    }
    uwagi.forEach((el) => {
      if (el.innerHTML) {
        const element = el.innerHTML.split(";");
        el.innerHTML = "";
        element.forEach((elem) => {
          el.innerHTML += elem + "<br>";
        });
      }
    });
    tr.forEach((el, i) => {
      if (i % 2 == 0) {
        el.classList.add("parzyste");
      }
    });
    tarch.forEach((el) => {
      if (el.textContent == "ANALOGOWY") {
        el.classList.add("cyfrowy");
      }
    });
    nav.addEventListener("mouseover", this.text_shadowing);
    nav.addEventListener("mouseout", this.text_unshadowing);
    nav_buttons.forEach((el) => {
      el.addEventListener("click", (e) => {
        nav_buttons.forEach((nav_el) => {
          document.querySelector("." + nav_el.dataset.src).style.display =
            "none";
        });
        const x = e.target.dataset.src;
        console.log(x);
        document.querySelector("." + x).style.display = "flex";
        // console.log(el);
      });
    });
  }
  handleCloning(e) {
    const div = e.target.closest(".podkresl").outerHTML;
    e.target.closest(".podkresl").insertAdjacentHTML("afterend", div);
    const div2 = e.target.closest(".podkresl").nextElementSibling;
    console.log(div2.firstElementChild);
    div2.firstElementChild.addEventListener(
      "click",
      this.handleCloning.bind(this)
    ); // Użycie bind() do zachowania kontekstu
  }

  dodaj_nowy_kontakt(e) {
    const kontakty = document.querySelector(".kontakty_table");
    kontakty.insertAdjacentHTML(
      "beforeend",
      `
    <tr class='closest'>
    <td><input name='imie_nazwisko[]' value=''></td>
    <td><input name='stanowisko_kontakt[]'value=''></td>
    <td><input name='mail_kontakt[]' value=''></td>
    <td><input name='tel_kontakt[]' value=''></td>
    <td><input type='button' value='X' class='kontakt_deleter  '></td>
  </tr>
 `
    );
    const ostatni =
      document.querySelectorAll(".kontakt_deleter")[
        document.querySelectorAll(".kontakt_deleter").length - 1
      ];
    ostatni.addEventListener("click", (e) => {
      e.target.closest(".closest").remove();
    });
  }
  braki_w_km(e) {
    const linijki = [...document.querySelectorAll(".linia")];
    const uwagi_ = document.querySelectorAll(".uwagi");
    console.log(linijki, uwagi_);
    uwagi.forEach((el, index) => {
      console.log(el.textContent);
      if (!el.textContent) {
        linijki[index].style.display = e.target.checked ? "none" : "table-row";
      }
    });
  }
  reduce_options() {
    firma_option.forEach((el) => {
      if (!el.textContent.includes(firma_editor.value))
        el.style.display = "none";
      else el.style.display = "block";
    });
  }

  delete_a_line(e) {
    console.log(e.target);
    e.target.closest(".podkresl").remove();
  }
  enter_new_line(e) {
    new_line.insertAdjacentHTML(
      "beforebegin",
      e.target.classList.contains("lol")
        ? ` <div class='podkresl'>
      <input name='kierowcy[]' >
      <input name='karta_kierowcy[]'>
      <input  name='umowa[]' >
      <input  name='etat[]' >
      <input  name='uwagi[]' >

      <input  name='odkiedy[]' >
      <input  name='dokiedy[]' >
      <input  name='zasadnicza[]' >
      <input  name='dyzur[]' >
      <input  name='premia[]' >
      <input  name='nadgodziny[]' >
      <input  name='nocne[]' >
      <input  name='odczyt[]' type='checkbox' value='true'  onchange='changeCheckboxValue(this)'><input type='hidden' name='odczyt[]' value='false'>

      <input type="button" value="usuń" class="line_deleting input_design">
      </div>`
        : `<div class="podkresl">
        <input name="pojazdy[]" value="" />
        <input type="checkbox" class="blokada_check" name="blokada[]" value="true" '.$checked.' onchange="changeCheckboxValue(this)">
        <input type="hidden" name="blokada[]" value="false">
        <input name="tachograf[]" value="" />
        <input name="uwagi[]" value="" />
        <input name="braki[]" value="" />
        <input type="button" value="usuń" class="line_deleting input_design">
      </div><br>`
    );
    // const wszystkkie_btn = [...document.querySelectorAll(".line_deleting")];
    // const ostatni_btn = wszystkkie_btn[wszystkkie_btn.length - 1];
    // console.log(ostatni_btn);
    // ostatni_btn.addEventListener("click", this.delete_a_line);

    const btn =
      document.querySelectorAll(".line_deleting")[
        document.querySelectorAll(".line_deleting").length - 1
      ];
    btn.addEventListener("click", (e) => {
      console.log(e.target);
      e.target.closest(".podkresl").remove();
    });
  }
  print(e) {
    const zrodlo = e.target.dataset.druk;
    let tabela = "";

    if (zrodlo == "drukuj_wszystko") {
      document.querySelectorAll(".drukowanie_wszystko").forEach((el, index) => {
        const textAREA = document.querySelector(
          ".generuj_raport_druk"
        ).innerHTML;
        if (index > 0)
          tabela += '<div style="page-break-before: always;"></div>';
        if (!el.classList.contains("generuj_raport_druk")) {
          tabela += el.outerHTML;
        } else {
          tabela += "<pre>" + textAREA + "</pre>";
        }
      });
    } else {
      tabela = document.querySelector(zrodlo).outerHTML;
    }

    // Dodajemy textarea do dokumentu tymczasowego (poza widocznością) w celu obliczenia jego pełnej wysokości.
    const tempTextArea = document.createElement("textarea");
    tempTextArea.style.position = "absolute";
    tempTextArea.style.left = "-9999px";
    tempTextArea.style.height = "auto";
    tempTextArea.value = document.querySelector("textarea").value;
    document.body.appendChild(tempTextArea);

    const newWindow = window.open("", "_blank");

    newWindow.document.write(
      "<html><head><title>PDF</title><link rel='stylesheet' href='style.css' /></head><body>"
    );
    newWindow.document.write(tabela);
    newWindow.document.write("</body></html>");

    newWindow.document.close();
    newWindow.onload = function () {
      // Zmieniamy tymczasową wysokość textarea na aktualną wysokość textarea.
      const textareaHeight = tempTextArea.scrollHeight + "px";
      document.querySelector("textarea").style.height = textareaHeight;

      newWindow.print();
      newWindow.close();

      // Przywracamy pierwotną wysokość textarea.
      document.querySelector("textarea").style.height = "auto";

      // Usuwamy tymczasowy textarea z dokumentu.
      document.body.removeChild(tempTextArea);
    };
  }
  text_shadowing(e) {
    if (e.target.classList.contains("nav")) {
      document.querySelectorAll(".nav").forEach((el) => {
        if (el !== e.target) {
          el.classList.add("shadowing");
        }
      });
    }
  }
  text_unshadowing(e) {
    if (e.target.classList.contains("nav")) {
      document.querySelectorAll(".nav").forEach((el) => {
        el.classList.remove("shadowing");
      });
    }
  }
}
const app = new App();
