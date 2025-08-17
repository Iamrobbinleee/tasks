import { defineStore } from 'pinia';
import axios from 'axios';

export const storeTask = defineStore('taskStore', {
    state: () => ({
        tasks: [],
        loading: false,
    }),
    actions: {
        async fetchTasks() {
            this.loading = true;
            const res = await axios.get('/api/tasks');
            this.tasks = res.data.tasks;
            this.loading = false;
        },
        async addTask(task) {
            await axios.post('/api/tasks', task);
            this.fetchTasks();
        },
        async updateTask(id, task) {
            await axios.put(`/api/tasks/${id}`, task);
            this.fetchTasks();
        },
        async deleteTask(id) {
            await axios.delete(`/api/tasks/${id}`);
            this.fetchTasks();
        }
    }
});
