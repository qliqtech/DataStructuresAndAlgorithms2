using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DataStructuresAndAlgorithms2
{
    public class Recursion
    {


        public void calculateiterative(int n) {

            while (n > 0) {

                int k = n * n;
                Console.WriteLine(k);
                n = n - 1;

            
            }

        
        }

        public void calculaterecursive(int n)
        {

            if (n > 0)
            {

                int k = n * n;
                Console.WriteLine(k);
                //   n = n - 1;

                calculaterecursive(n - 1);
            }


        }



    }
}
