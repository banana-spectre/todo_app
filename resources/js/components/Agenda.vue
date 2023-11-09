<template lang="">
    <div class="p-4 w-6/12">
        <div class="flex justify-end w-full relative pr-6">
            <button class="bi bi-three-dots text-gray-600 text-3xl" @click="dropDownHandler">
                <DropDown v-if="isDropDownOpen" :getSubjects="getSubjects" :todo="todo" :editAgendaTrigger="editAgendaTrigger" />
            </button>
        </div>
        <div class="rounded-xl py-3 shadow-[0px_6px_23.4px_3px_#BCBCBC]">
            <div class="flex justify-center w-full p-2">
                <h2 class="font-roboto_medium text-3xl text-[#5A00E3] font-medium">{{ todo.subject_name }}</h2>
            </div>
            <div v-for="item in todo.todo_items" class="flex flex-col text-xl font-medium ml-4"> 
                <div class="flex w-full items-center p-2 gap-4">
                    <input type="checkbox" class="rounded-full h-5 w-5 text-[#5A00E3] focus:ring-0"
                    :checked="item.is_complete" id="checkbox" 
                    @change="checkboxHandler(item.id, item.is_complete)"/>
                    <p>{{ item.todo_name }}</p>
                </div>
            </div>
            <div class="flex p-3 gap-2 overflow-x-auto mx-4" :style="{'justify-content' : todo.files.length>3 ? 'start' : 'center'}">
                <div v-for="file in todo.files" 
                class="border-2 border-[#934DFF] rounded-lg h-10 w-25 flex justify-center items-center relative group px-2 text-[#AD77FF]">
                    <p class="truncate text-ellipsis">{{ file.file_name.split(".")[0] }}</p>
                    <p>{{ file.file_name.split(".")[file.file_name.split(".").length-1] }}</p>
                </div>
            </div>
        </div>
        <EditAgenda v-if="isEditAgendaOpen" :todo="todo" :editAgendaTrigger="editAgendaTrigger" />
    </div>
</template>
<script>
import DropDown from './DropDown.vue';
import EditAgenda from './EditAgenda.vue'

export default {
    props: {
        todo: Object,
        getSubjects: Function
    },
    components: {
        DropDown,
        EditAgenda,
    },
    data() {
        return {
           isDropDownOpen: false,
           isEditAgendaOpen: false
        }
    },
    methods: {
        checkboxHandler(id, is_complete) {
            axios.patch(`/api/todo_items/${id}`, {
                "is_complete": !is_complete,
            })
            .catch((error) => {
                console.error(error)
            })
        },
        dropDownHandler() {
            this.isDropDownOpen = !this.isDropDownOpen
        },
        editAgendaTrigger() {
            window.scrollTo(0,0)
            this.isEditAgendaOpen = !this.isEditAgendaOpen
            this.$props.getSubjects()
        }
    },
    watch: {
        isEditAgendaOpen() {
            if(this.isEditAgendaOpen) {
                document.documentElement.style.overflow = 'hidden'
                return
            }
            document.documentElement.style.overflow = 'auto'
        }
    }
}
</script>
