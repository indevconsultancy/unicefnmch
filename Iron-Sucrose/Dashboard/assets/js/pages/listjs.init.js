// Initialize variables
let customerList;
const checkAll = document.getElementById("checkAll");
const perPage = 8;
let editlist = false;

// Handle 'check all' checkbox click
if (checkAll) {
  checkAll.onclick = function () {
    const checkboxes = document.querySelectorAll('.form-check-all input[type="checkbox"]');
    checkboxes.forEach((checkbox) => {
      checkbox.checked = checkAll.checked;
      checkbox.closest("tr").classList.toggle("table-active", checkAll.checked);
    });
  };
}

// List.js options
const options = {
  valueNames: ["id", "customer_name", "email", "date", "phone", "status"],
  page: perPage,
  pagination: true,
  plugins: [ListPagination({ left: 2, right: 2 })]
};

// Initialize List.js if element exists
if (document.getElementById("customerList")) {
  customerList = new List("customerList", options).on("updated", (list) => {
    document.getElementsByClassName("noresult")[0].style.display = list.matchingItems.length === 0 ? "block" : "none";

    const isFirstPage = list.i === 1;
    const isLastPage = list.i > list.matchingItems.length - list.page;

    document.querySelector(".pagination-prev").classList.toggle("disabled", isFirstPage);
    document.querySelector(".pagination-next").classList.toggle("disabled", isLastPage);

    document.querySelector(".pagination-wrap").style.display = list.matchingItems.length <= perPage ? "none" : "flex";

    if (list.matchingItems.length === perPage) {
      document.querySelector(".pagination.listjs-pagination").firstElementChild.children[0].click();
    }
  });
}

// Load initial data using XMLHttpRequest
const xhttp = new XMLHttpRequest();
xhttp.onload = function () {
  const data = JSON.parse(this.responseText);
  data.forEach((item) => {
    customerList.add({
      id: `<a href="javascript:void(0);" class="fw-medium link-primary">#VZ${item.id}</a>`,
      customer_name: item.customer_name,
      email: item.email,
      date: item.date,
      phone: item.phone,
      status: isStatus(item.status)
    });
    customerList.sort("id", { order: "desc" });
    refreshCallbacks();
  });
  customerList.remove("id", '<a href="javascript:void(0);" class="fw-medium link-primary">#VZ2101</a>');
};
xhttp.open("GET", "assets/json/table-customer-list.json");
xhttp.send();

// Helper function for status badge
function isStatus(status) {
  switch (status) {
    case "Active":
      return `<span class="badge bg-success-subtle text-success text-uppercase">${status}</span>`;
    case "Block":
      return `<span class="badge bg-danger-subtle text-danger text-uppercase">${status}</span>`;
  }
}

// Refresh remove and edit button callbacks
function refreshCallbacks() {
  const removeBtns = document.getElementsByClassName("remove-item-btn");
  const editBtns = document.getElementsByClassName("edit-item-btn");

  Array.from(removeBtns).forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const row = e.target.closest("tr");
      const itemId = row.children[1].innerText;
      const items = customerList.get({ id: itemId });

      items.forEach((item) => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(item._values.id, "text/html");
        const idHTML = doc.body.firstElementChild.innerHTML;

        if (idHTML === itemId) {
          document.getElementById("delete-record").addEventListener("click", () => {
            customerList.remove("id", doc.body.firstElementChild.outerHTML);
            document.getElementById("deleteRecordModal").click();
          });
        }
      });
    });
  });

  Array.from(editBtns).forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const row = e.target.closest("tr");
      const itemId = row.children[1].innerText;
      const items = customerList.get({ id: itemId });

      items.forEach((item) => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(item._values.id, "text/html");
        const idHTML = doc.body.firstElementChild.innerHTML;

        if (idHTML === itemId) {
          editlist = true;
          idField.value = idHTML;
          customerNameField.value = item._values.customer_name;
          emailField.value = item._values.email;
          dateField.value = item._values.date;
          phoneField.value = item._values.phone;

          if (statusVal) statusVal.destroy();
          statusVal = new Choices(statusField);

          const statusDoc = new DOMParser().parseFromString(item._values.status, "text/html");
          statusVal.setChoiceByValue(statusDoc.body.firstElementChild.innerHTML);
        }
      });
    });
  });
}

// Clear form fields
function clearFields() {
  customerNameField.value = "";
  emailField.value = "";
  dateField.value = "";
  phoneField.value = "";
}

// Handle 'check all' checkbox row highlighting
function ischeckboxcheck() {
  const checkboxes = document.getElementsByName("checkAll");
  Array.from(checkboxes).forEach((checkbox) => {
    checkbox.addEventListener("click", (e) => {
      e.target.closest("tr").classList.toggle("table-active", e.target.checked);
    });
  });
}
