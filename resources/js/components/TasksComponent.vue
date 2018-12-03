<template>
    
    <div class="container mt-4">
    
        <div class="card mb-4" v-show="!creating">
            <div class="card-body">
                <button type="submit" class="btn btn-success float-right" @click="create()">Nuevo</button>
            </div>
        </div>
    
        <form @submit.prevent="store">
            
            <div class="card mb-4" v-show="creating">
                <div class="card-header">
                    <button type="button" class="close" @click="creating = false"><span>&times;</span></button>
                    New Task
                </div>
                <div class="card-body">
                    
                    <div class="form-group">
                        <input type="text" v-model="task.name" class="form-control" maxlength="255" required placeholder="Insert for title">
                    </div>
                    
                    <div class="form-group mb-0">
                        <textarea v-model="task.description" class="form-control" cols="3" maxlength="255" required placeholder="Insert for description"></textarea>
                    </div>
                    
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-light" @click="creating = false">Cancelar</button>
                </div>
            </div>
            
        </form>
    
        <div class="card">
            <div class="card-header">
                Current Tasks
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item" v-for="task in tasks" :key="task.id">
                    <button type="button" class="btn btn-danger btn-sm float-right" @click="destroy(task.id)">Eliminar</button>
                    <h5 contenteditable="true" @blur="update($event, task, 'name')">{{ task.name }}</h5>
                    <p contenteditable="true" @blur="update($event, task, 'description')">{{ task.description }}</p>
                </li>
            </ul>
            
        </div>
    
    </div>
    
</template>

<script>
export default {
    data() {
        return {
            tasks: [],
            task: {
                name: '',
                description: '',
            },
            creating: false,
            editings: [],
            maxlength: 255,
        }
    },
    mounted() {
        window.axios.get('/tasks').then(resp => {
            this.tasks = resp.data;
        }).catch(e => {
            console.log(e);
        });
    },
    methods: {
        create() {
            this.task.name = '';
            this.task.description = '';
            this.creating = true;
        },
        store() {
            window.axios.post('/tasks', {name: this.task.name, description: this.task.description}).then(resp => {
                this.tasks.unshift(resp.data);
                this.creating = false;
            }).catch(e => {
                console.log(e);
            });
        },
        update(event, task, field) {
            if(event.target.textContent.length > this.maxlength){
                event.target.textContent = event.target.textContent.substring(0, this.maxlength);
            }
            if(task[field] === event.target.textContent){
                return;
            }
            window.axios.put(`/tasks/${task.id}`, {[field]: event.target.textContent}).then(resp => {
                task[field] = event.target.textContent;
            }).catch(e => {
                console.log(e);
                event.target.textContent = task[field];
            });
        },
        destroy(id) {
            window.axios.delete(`/tasks/${id}`).then(resp => {
                let index = this.tasks.findIndex(task => task.id === id);
                this.tasks.splice(index, 1);
            }).catch(e => {
                console.log(e);
            });
        },
    }
}
</script>

<style type="text/css">
    [contenteditable] {
        border: 1px solid #fff;
        padding: 3px;
    }
    [contenteditable]:hover {
        border: 1px dashed #ccc;
    }
</style>