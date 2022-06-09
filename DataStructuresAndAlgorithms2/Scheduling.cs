using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DataStructuresAndAlgorithms2
{
    public class Scheduling
    {

        public int minSwaps(List<int> arr)
        {
            int n = arr.Count;

            // Create two arrays and
            // use as pairs where first
            // array is element and second array
            // is position of first element
            List<KeyValuePair<int, int>> arrpos =
              new List<KeyValuePair<int, int>>();
            for (int i = 0; i < n; i++)
                arrpos.Add(new KeyValuePair<int,
                           int>(arr[i], i));

            // Sort the array by array element values to
            // get right position of every element as the
            // elements of second array.
            arrpos.Sort((a, b) => a.Key - b.Key);

            // To keep track of visited elements. Initialize
            // all elements as not visited or false.
            Boolean[] vis = new Boolean[n];


            // Initialize result
            int ans = 0;

            // Traverse array elements
            for (int i = 0; i < n; i++)
            {

                // already swapped and corrected or
                // already present at correct pos
                if (vis[i] || arrpos[i].Value == i)
                    continue;

                // find out the number of  node in
                // this cycle and add in ans
                int cycle_size = 0;
                int j = i;
                while (!vis[j])
                {
                    vis[j] = true;

                    // move to next node
                    j = arrpos[j].Value;
                    cycle_size++;
                }

                // Update answer by adding current cycle.
                if (cycle_size > 0)
                {
                    ans += (cycle_size - 1);
                }
            }

            // Return result
            return ans;
        }
    }

    // Driver class


}

        
    

