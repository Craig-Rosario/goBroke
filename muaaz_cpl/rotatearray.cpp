#include <stdio.h>

void rotateLeftByOne(int arr[], int size)
{
    if (size <= 1) return;
    int first = arr[0];
    for (int i = 0; i < size - 1; i++)
    {
        arr[i] = arr[i + 1];
    }
    arr[size - 1] = first;
}


void rotateRightByOne(int arr[], int size
{
    if (size <= 1) return;
    int last = arr[size - 1];
    for (int i = size - 1; i > 0; i--)
    {
        arr[i] = arr[i - 1];
    }
    arr[0] = last;
}