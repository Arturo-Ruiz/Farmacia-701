// CARGAR PEDIDOS EN TABLA

// Variables globales

const divProducto = document.querySelector(".product__container");
const tablaPedido = document.querySelector("#products__table");

// let arrayProducto = [];

// Funciones

const crearProducto = (nombreProducto, precio, cantidad, total) => {
  let producto = {
    nombre: nombreProducto,
    precio: precio,
    cantidad: 1,
    total: precio,
  };

  arrayProducto.push(producto);
};

//   Guardar producto en sessionstorage

const guardarDB = () => {
  sessionStorage.setItem("item", JSON.stringify(arrayProducto));
};

const cerrar = () => {
  sessionStorage.removeItem("item");
};

// Insertar datos en tabla

const insertarTabla = () => {
  tablaPedido.innerHTML = "";
  let itemsCart = JSON.parse(sessionStorage.getItem("item"));

  // console.log("arrayProducto");

  if (itemsCart === null) {
    console.log("no hay productos");
  } else {
    itemsCart.forEach((element) => {
      tablaPedido.innerHTML += `<tr>
                    <td scope="row">${element.nombre}</td>
                    <td class="text-center">
                        
                            <div class=" d-flex input__cant">
                                <a class="cart_cantidad_restar btn btn-outline-dark" href="#"> - </a>
                                <p class="cart_quantity">${element.cantidad}</p>
                                <a class="cart_cantidad_sumar btn btn-outline-dark" href="#"> + </a>
                            </div>
                        
                    </td>
                    <td class="text-center td-total"><p>Bs.${element.total}</p> </td>
                    <td class="text-center "><button class="button_delete" onclick="borrarItem()"><i class="fa-solid fa-trash "></i></button></td>
                </tr>`;
    });
  }
};

// Listeners para guardar productos

divProducto.addEventListener("click", (e) => {
  e.preventDefault();
  // console.log(e);

  let itemsCart = JSON.parse(sessionStorage.getItem("item"));
  arrayProducto = itemsCart;

  // console.log(res.nombre);
  if (e.target.innerHTML === "Agregar") {
    let nombreProducto = e.path[1].childNodes[1].innerText;
    let precio = e.path[1].childNodes[3].children[1].innerText;
    if (arrayProducto === null) {
      arrayProducto = [];
      crearProducto(nombreProducto, precio);
      guardarDB();
      // cantItems();
      itemsCount();
    } else {
      let res = arrayProducto.find(
        (element) => element.nombre == nombreProducto
      );

      if (res === undefined) {
        crearProducto(nombreProducto, precio);
        guardarDB();
        // cantItems();
        itemsCount();
      }
    }

    // Cambiar boton agregar por sumar y restar
    let addRest__div = e.path[1].childNodes[7];
    // console.log(nombreProducto);

    if (arrayProducto.length > 0 && e.target.innerHTML === "Agregar") {
      e.path[0].classList.replace("d-block", "d-none");

      arrayProducto.map((element) => {
        if (nombreProducto === element.nombre) {
          addRest__div.innerHTML = `
                <a class="cart_cantidad_restar btn btn-primary" href="#"> - </a>
                <p class="cart_quantity">${element.cantidad}</p>
                <a class="cart_cantidad_sumar btn btn-primary" href="#"> + </a>
                `;
        }
      });
    }
  }

  // sumar y restar cantidad del mismo productos al carrito
  let cart = JSON.parse(sessionStorage.getItem("item")); //Llamar items nuevamente despues de llenarlo
  let producto = e.path[2].childNodes[1].innerText;
  let contenedor__sumaResta = e.path[1];

  if (e.target.innerText === "+") {
    // console.log(producto);
    cart.map((e) => {
      if (producto == e.nombre) {
        e.cantidad = ++e.cantidad;
        e.total = e.precio * e.cantidad;
        e.total = e.total.toFixed(2);

        contenedor__sumaResta.innerHTML = `
                    <a class="cart_cantidad_restar btn btn-primary" href="#"> - </a>
                    <p class="cart_quantity">${e.cantidad}</p>
                    <a class="cart_cantidad_sumar btn btn-primary" href="#"> + </a>
                    `;

        cart.indexOf(e, e);

        sessionStorage.setItem("item", JSON.stringify(cart));
      }
      itemsCount();
    });
  } else if (e.target.innerText === "-") {
    let addButton = e.path[2].children[2];
    cart.map((e) => {
      if (producto == e.nombre) {
        e.cantidad = --e.cantidad;
        e.total = e.precio * e.cantidad;
        e.total = e.total.toFixed(2);

        contenedor__sumaResta.innerHTML = `
                    <a class="cart_cantidad_restar btn btn-primary" href="#"> - </a>
                    <p class="cart_quantity">${e.cantidad}</p>
                    <a class="cart_cantidad_sumar btn btn-primary" href="#"> + </a>
                    `;
        cart.indexOf(e, e);

        sessionStorage.setItem("item", JSON.stringify(cart));

        if (e.nombre === producto && e.cantidad === 0) {
          let i = JSON.parse(sessionStorage.getItem("item"));
          let res = i.filter((e) => e.nombre != producto);

          sessionStorage.setItem("item", JSON.stringify(res));
          contenedor__sumaResta.innerHTML = "";
          addButton.classList.replace("d-none", "d-block");
        }
        itemsCount();
      }
    });
  }
});

// Contador de items en cart-index

function itemsCount() {
  let divCart = document.querySelector(".button__cart");
  let items = JSON.parse(sessionStorage.getItem("item"));

  let temp = items.map(function (item) {
    return parseFloat(item.cantidad);
  });

  let sumItems = temp.reduce(function (prev, next) {
    return prev + next;
  }, 0);

  if (sumItems > 0 || sumItems === 0) {
    divCart.innerHTML = `
              <i class="fa-solid fa-cart-shopping"></i>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-secondary">${sumItems}</span>
              `;
  } else {
    console.log("no se pudo cargar total de items");
  }
}
