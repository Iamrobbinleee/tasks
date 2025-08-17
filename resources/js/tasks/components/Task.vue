<template>
  <div class="p-4 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4 text-center">To-Do List by Lee</h1>
    <div class="mb-4">
      <input v-model="newTask.title" placeholder="Task title" class="border p-2 w-full mb-2" />
      <input v-model="newTask.description" placeholder="Task description" class="border p-2 w-full mb-2" />
      <input v-model="newTask.category" placeholder="Category" class="border p-2 w-full mb-2" />
      <input type="date" v-model="newTask.due_date" class="border p-2 w-full mb-2" />
      <select v-model="newTask.priority" class="border p-2 w-full mb-2">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
      </select>
      <button @click="addTask" class="ui green button circular text-white px-4 py-2" style="margin-top: 10px;">Add Task</button>
    </div>
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
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue';
import { storeTask } from '../task';

const store = storeTask();
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

watch(() => store.tasks, () => {
  initializetTabs();
});
</script>
