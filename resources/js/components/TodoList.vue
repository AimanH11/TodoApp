<template>
  <div class="todo-list-container">
    <div class="header">
      <h1>Todo List</h1>
      <button @click="showAddForm">Add Todo</button>
    </div>

    <form v-if="showForm" @submit.prevent="addItem" class="add-form">
      <label for="newItem">New Todo:</label>
      <div class="input-group">
        <input type="text" v-model="newItem" required />
        <button type="submit">Add</button>
      </div>
    </form>

    <ul class="todo-items">
      <li v-for="item in todoItems" :key="item.id" class="todo-item">
        <span :class="{ completed: item.completed }">{{ item.title }}</span>
        <div>
          <button @click="completeItem(item.id)">Complete</button>
          <button @click="deleteItem(item.id)">Delete</button>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      todoItems: [],
      newItem: "",
      showForm: false,
    };
  },
  mounted() {
    axios.post('/', { title: this.newItem })
  .then(() => {
    this.newItem = "";
    this.fetchTodoItems();
    this.showForm = false;
  })
  .catch((error) => {
    console.error("Error adding todo item:", error);
  });

  },
  methods: {
    fetchTodoItems() {
      axios.get('/').then((response) => {
        this.todoItems = response.data;
      });
    },
    addItem() {
      if (this.newItem.trim() !== "") {
        axios.post('/todo', { title: this.newItem }).then(() => {
          this.newItem = "";
          this.fetchTodoItems();
          this.showForm = false;
        });
      }
    },
    completeItem(itemId) {
      axios.put(`/todo/${itemId}`, { completed: true }).then(() => {
        this.fetchTodoItems();
      });
    },
    deleteItem(itemId) {
      axios.delete(`/todo/${itemId}`).then(() => {
        this.fetchTodoItems();
      });
    },
    showAddForm() {
      this.showForm = true;
    },
  },
};
</script>

<style scoped>
.todo-list-container {
  max-width: 600px;
  margin: auto;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.header button {
  cursor: pointer;
  padding: 8px;
  background-color: #4caf50;
  color: white;
  border: none;
}

.add-form {
  margin-bottom: 16px;
}

.input-group {
  display: flex;
}

.input-group input {
  flex: 1;
  padding: 8px;
}

.input-group button {
  cursor: pointer;
  padding: 8px;
  background-color: #4caf50;
  color: white;
  border: none;
}

.todo-items {
  list-style-type: none;
  padding: 0;
}

.todo-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border: 1px solid #ddd;
  padding: 10px;
  margin-top: 8px;
}

.todo-item span {
  flex: 1;
}

.todo-item.completed span {
  text-decoration: line-through;
  color: #888;
}

.todo-item button {
  cursor: pointer;
  padding: 6px;
  margin-left: 8px;
  border: none;
  color: white;
}

.todo-item button.complete {
  background-color: #4caf50;
}

.todo-item button.delete {
  background-color: #f44336;
}

</style>
