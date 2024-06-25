document.addEventListener("alpine:init", () => {
  Alpine.data("products", () => ({
    items: [
      { id: 1, name: "Arabica", img: "1.jpg", price: 14.99 },
      { id: 2, name: "Robusta", img: "2.jpg", price: 9.99 },
      { id: 3, name: "Liberica", img: "3.jpg", price: 4.99 },
      { id: 4, name: "Excelsa", img: "4.jpg", price: 6.99 },
      { id: 5, name: "Sumatra", img: "5.jpg", price: 12.99 },
    ],
  }));

  Alpine.store("cart", {
    items: [],
    total: 0,
    quantity: 0,
    add(newItem) {
      const cartItem = this.items.find((item) => item.id === newItem.id);
      if (!cartItem) {
        this.items.push({ ...newItem, quantity: 1, total: newItem.price });
        this.quantity++;
        this.total += newItem.price;
      } else {
        this.items = this.items.map((item) => {
          if (item.id !== newItem.id) {
            return item;
          } else {
            item.quantity++;
            item.total = item.price * item.quantity;
            this.quantity++;
            this.total += item.price;
            return item;
          }
        });
      }
    },
    remove(id) {
      const cartItem = this.items.find((item) => item.id === id);
      if (cartItem.quantity > 1) {
        this.items = this.items.map((item) => {
          if (item.id !== id) {
            return item;
          } else {
            item.quantity--;
            item.total = item.price * item.quantity;
            this.quantity--;
            this.total -= item.price;
            return item;
          }
        });
      } else if (cartItem.quantity === 1) {
        this.items = this.items.filter((item) => item.id !== id);
        this.quantity--;
        this.total -= cartItem.price;
      }
    },
  });

  document.getElementById('checkoutForm').addEventListener('submit', async function (event) {
    event.preventDefault();

    const customer = {
      user_id: 1,
      name: document.getElementById('name').value,
      email: document.getElementById('email').value,
      phone: document.getElementById('phone').value,
    };

    const cart = Alpine.store("cart").items;

    try {
      const response = await fetch('checkout.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ customer, cart })
      });

      const result = await response.json();

      if (result.success) {
        document.querySelector('#success-modal').style.display = 'block';
        Alpine.store("cart").items = [];
        Alpine.store("cart").total = 0;
        Alpine.store("cart").quantity = 0;
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error('Error:', error);
    }
  });
});
