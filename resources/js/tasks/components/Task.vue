<template>
  <div class="p-4 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4 text-center">To-Do List by Lee</h1>
    <form @submit.prevent="addTask">
      <div class="mb-4">
        <input v-model="newTask.title" placeholder="Task title" class="border p-2 w-full mb-2"  required/>
        <input v-model="newTask.description" placeholder="Task description" class="border p-2 w-full mb-2"  required/>
        <input v-model="newTask.category" placeholder="Category" class="border p-2 w-full mb-2"  required/>
        <input type="date" v-model="newTask.due_date" class="border p-2 w-full mb-2"/>
        <select v-model="newTask.priority" class="border p-2 w-full mb-2" required>
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>
        <button type="submit" class="ui green button circular text-white px-4 py-2" style="margin-top: 10px;">Add Task</button>
      </div>
    </form>
  </div>
  
  <!-- Task List -->
  <div class="p-4 max-w-lg mx-auto">
    <div v-if="store.loading">Loading...</div>
    <div v-else>
      <div v-if="!store.tasks || store.tasks.length === 0">
        <p>NO TASKS YET.</p>
      </div>
      <div v-else>
        <!-- Tabs -->
        <div class="ui top attached tabular menu">
          <a class="item ui blue active" data-tab="low">LOW</a>
          <a class="item ui yellow" data-tab="medium">MEDIUM</a>
          <a class="item ui red" data-tab="high">HIGH</a>
        </div>

        <!-- LOW tasks -->
        <div class="ui bottom attached tab segment active" data-tab="low">
          <ul>
            <li v-for="task in store.tasks.filter(t => t.priority === 'low')" :key="task.id"
                class="border-b p-2 flex justify-between">
              <div>
                <strong>{{ task.title }}</strong> - {{ task.category }}
                <div class="text-sm text-gray-500">Due: {{ task.due_date || 'No date' }}</div>
              </div>
              <div class="ui buttons">
                <button @click="store.viewTask(task.id)" class="ui yellow basic button">View</button>
                <button @click="store.deleteTask(task.id)" class="ui red basic button">Delete</button>
              </div>
            </li>
          </ul>
        </div>

        <!-- MEDIUM tasks -->
        <div class="ui bottom attached tab segment" data-tab="medium">
          <ul>
            <li v-for="task in store.tasks.filter(t => t.priority === 'medium')" :key="task.id"
                class="border-b p-2 flex justify-between">
              <div>
                <strong>{{ task.title }}</strong> - {{ task.category }}
                <div class="text-sm text-gray-500">Due: {{ task.due_date || 'No date' }}</div>
              </div>
             <div class="ui buttons">
                <button @click="store.viewTask(task.id)" class="ui yellow basic button">View</button>
                <button @click="store.deleteTask(task.id)" class="ui red basic button">Delete</button>
              </div>
            </li>
          </ul>
        </div>

        <!-- HIGH tasks -->
        <div class="ui bottom attached tab segment" data-tab="high">
          <ul>
            <li v-for="task in store.tasks.filter(t => t.priority === 'high')" :key="task.id"
                class="border-b p-2 flex justify-between">
              <div>
                <strong>{{ task.title }}</strong> - {{ task.category }}
                <div class="text-sm text-gray-500">Due: {{ task.due_date || 'No date' }}</div>
              </div>
              <div class="ui buttons">
                <button @click="store.viewTask(task.id)" class="ui yellow basic button">View</button>
                <button @click="store.deleteTask(task.id)" class="ui red basic button">Delete</button>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- View Task Modal -->
  <div v-if="store.selectedTask" class="ui dimmer modals visible active">
    <div class="ui modal active">
      <div class="header">View Task</div>
      <div class="content">
        <p><strong>Title:</strong> {{ store.selectedTask.title }}</p>
        <p><strong>Description:</strong> {{ store.selectedTask.description }}</p>
        <p><strong>Category:</strong> {{ store.selectedTask.category }}</p>
        <p><strong>Priority:</strong> {{ store.selectedTask.priority }}</p>
        <p><strong>Due Date:</strong> {{ store.selectedTask.due_date || 'No date' }}</p>
      </div>
      <div class="actions">
        <div class="ui green button" @click="updTask(store.selectedTask)">Edit</div>
        <div class="ui button" @click="store.closeTask()">Close</div>
      </div>
    </div>
  </div>

  <!-- Update Task Modal -->
  <div v-if="editTask" class="ui dimmer modals visible active">
    <div class="ui modal active">
      <div class="header">Update Task</div>
      <div class="content">
        <div class="ui form">
          <div class="field">
            <label>Title</label>
            <input v-model="editTask.title" />
          </div>
          <div class="field">
            <label>Description</label>
            <input v-model="editTask.description" />
          </div>
          <div class="field">
            <label>Category</label>
            <input v-model="editTask.category" />
          </div>
          <div class="field">
            <label>Due Date</label>
            <input type="date" v-model="editTask.due_date" />
          </div>
          <div class="field">
            <label>Priority</label>
            <select v-model="editTask.priority" class="ui dropdown">
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
            </select>
          </div>
        </div>
      </div>
      <div class="actions">
        <div class="ui green button" @click="saveEdit">Update</div>
        <div class="ui button" @click="cancelEdit">Cancel</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue';
import { storeTask } from '../task';

const store = storeTask();
const editTask = ref(null);
const newTask = ref({
    title: '',
    description: '',
    category: '',
    due_date: '',
    priority: 'low'
});

onMounted(async () => {
    await store.fetchTasks();
    initializetTabs();
});

function initializetTabs() {
  nextTick(() => {
    $('.menu .item').tab();
  });
}

function addTask() {
    if (!newTask.value.title) return;
    store.addTask(newTask.value);
    newTask.value = { title: '', description: '', category: '', due_date: '', priority: 'low' };
    initializetTabs();
}

function updTask(task) {
  editTask.value = { ...task };
  store.closeTask();
}

function cancelEdit() {
  editTask.value = null;
}

async function saveEdit() {
  if (editTask.value) {
    await store.updateTask(editTask.value.id, editTask.value);
    editTask.value = null;
  }
}

watch(() => store.tasks, () => {
  initializetTabs();
});
</script>

<style scoped>
.ui.modal.active {
  position: fixed !important;
  top: 50% !important;
  left: 50% !important;
  transform: translate(-50%, -50%) !important;
  z-index: 1000;
  max-width: 500px;
  width: 90%;
}

.ui.modal.active .content {
  max-height: 60vh;
  overflow-y: auto;
}
</style>
