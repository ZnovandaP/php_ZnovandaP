<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Soal 1 A</title>
</head>

<body>
  <h1>
    Soal 1 A: Zidane Novanda Putra
  </h1>

  <form>
    <div>
      <label for="row-input">
        Inputkan Jumlah Baris:
      </label>
      <input type="number" min="1" name="row" id="row-input">
    </div>
    <div style="margin-top: 1rem;">
      <label for="column-input">
        Inputkan Jumlah Kolom:
      </label>
      <input type="number" min="1" name="column" id="column-input">
    </div>

    <button style="margin-top: 1rem;" id="submit-row">
      submit
    </button>
  </form>

  <br>
  <hr class="hr-dynamic">
  <br>

  <form id="form-list">
    <div id="row-container">
    </div>

    <button style="margin-top: 1rem;" id="submit-list">
      submit
    </button>
  </form>

  <br>
  <hr class="hr-dynamic">
  <br>

  <ul style="list-style-type: none;" id="result-list">
  </ul>

</body>

<script>
  const DATA_ROW = []
  const DATA_LIST = []

  const rowInput = document.querySelector('#row-input');
  const columnInput = document.querySelector('#column-input');
  const buttonSubmitRow = document.querySelector('#submit-row');

  const rowContainer = document.querySelector('#row-container');

  const showButtonAndSeparator = () => {
    document.querySelector('#submit-list').style.display = !DATA_ROW.length ? 'none' : 'block';
    document.querySelectorAll('.hr-dynamic').forEach((hr) => {
      hr.style.display = !DATA_ROW.length ? 'none' : 'block';
    })
  }

  const renderRowAndColumn = (row, columns) => {
    let columnsTemplate = '';

    for (const column of columns) {
      let pattern = `${row}.${column.column}`
      columnsTemplate += `
      <div>
        <label for="row-column-input">
          ${pattern}
        </label>
        <input type="text" name="${pattern}" id="row-column-input">
      </div>
      `;
    }

    return `
    <br/>
    <div id="row-container" style="display: flex; gap: .5rem; flex-wrap: wrap;">
      ${columnsTemplate}
    </div>
    <br/>
    `
  }

  buttonSubmitRow.addEventListener('click', (e) => {
    e.preventDefault();
    rowContainer.innerHTML = '';
    const row = rowInput.value;
    const column = columnInput.value;

    if (!row || !column) {
      alert('row dan column harus diisi')
      return;
    };

    if (row < 0 || column < 0) {
      alert('row dan column harus lebih besar dari 0')
      return;
    };

    if (DATA_ROW.length) {
      DATA_ROW.splice(0, DATA_ROW.length)
      DATA_LIST.splice(0, DATA_LIST.length)
    };

    for (let indexRow = 0; indexRow < row; indexRow++) {
      DATA_ROW.push({
        row: indexRow + 1,
        column: Array.from({ length: column }, (_, indexColumn) => {
          return {
            column: indexColumn + 1,
            value: ''
          }
        })
      })
    }

    for (const row of DATA_ROW) {
      rowContainer.innerHTML += renderRowAndColumn(row.row, row.column);
    }

    showButtonAndSeparator();
  })

  const renderList = (data) => {
    return `
    <li>
        <b>${data.row}.${data.column}: ${data.value || '-'}</b>
    </li>
    `
  }
  document.getElementById("form-list").addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    if (DATA_LIST.length) {
      DATA_LIST.splice(0, DATA_LIST.length)
    };

    formData.forEach((value, key) => {
      const row = key.split('.')[0];
      const column = key.split('.')[1];
      DATA_LIST.push({
        row: row,
        column: column,
        value: value
      })
    });

    const resultList = document.getElementById("result-list");
    resultList.innerHTML = '';
    for (const data of DATA_LIST) {
      resultList.innerHTML += renderList(data);
    }

    showButtonAndSeparator();
  });

  addEventListener('DOMContentLoaded', showButtonAndSeparator);
</script>

</html>