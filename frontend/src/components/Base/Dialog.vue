<template>
  <q-dialog v-model="fixed">
      <q-card>
        <q-card-section>
          <slot name="title"/>
        </q-card-section>

        <q-separator />

        <q-card-section style="max-height: 50vh" class="scroll">
          <slot name="body"/>
        </q-card-section>

        <q-separator />

        <q-card-actions align="right">
          <slot name="bottom"/>
        </q-card-actions>
      </q-card>
    </q-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const fixed = ref(false)
</script>
