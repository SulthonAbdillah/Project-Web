document.addEventListener("alpine:init", () => {
  Alpine.data("products", () => ({
    items: [
      { id: 1, name: "Arabica", img: "1.jpg ", price: 14.99 },
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
      // cek ada barang yang sama atau nggak di cart
      const cartItem = this.items.find((item) => item.id === newItem.id);

      //   jika blum ada atu masih kosong
      if (!cartItem) {
        this.items.push({ ...newItem, quantity: 1, total: newItem.price });
        this.quantity++;
        this.total += newItem.price;
      } else {
        //kalo barang udah adda cek apakah barangnya sama atau beda sama yang di cart
        this.items = this.items.map((item) => {
          // kalo barangnya beda
          if (item.id !== newItem.id) {
            return item;
          } else {
            // klo barangan udah ada tambah kuantiti dan totalnya
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
      // ambil item yg mau dihapus pake id
      const cartItem = this.items.find((item) => item.id === id);

      //   kalo item lebih sari satu
      if (cartItem.quantity > 1) {
        // kita cari satu2
        this.items = this.items.map((item) => {
          //jika bukan barang yg di klik
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
});

// Konversi USD
const dollar = (number) => {
  return new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
    minimumFractionDigits: 2, // Minimum number of fraction digits
  }).format(number);
};
