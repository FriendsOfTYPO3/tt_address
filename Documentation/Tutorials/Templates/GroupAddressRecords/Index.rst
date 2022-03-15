.. include:: /Includes.rst.txt


Group address records
---------------------

.. tip::
	This is a feature delivered by fluid, so you can use it also in other extensions and projects.


The following example will group all given address records by the property "firstCategory".

.. code-block:: html

	<f:if condition="{addresses}">
		<f:then>
			<div style="border:1px solid red">
				<f:groupedFor each="{addresses}" as="groupedAddresses" groupBy="firstCategory" groupKey="category">
					<div style="border:1px solid blue;padding:10px;margin:10px;">
						<h1>{category.title}</h1>
						<f:for each="{groupedAddresses}" as="address">
							<div style="border:1px solid pink;padding:5px;margin:5px;">
								{address.firstName} {address.lastName}
							</div>
						</f:for>
					</div>
				</f:groupedFor>
			</div>
		</f:then>
		<f:else>
			<div class="no-addresses-found">
				No addresses found.
			</div>
		</f:else>
	</f:if>


Keep an eye on performance!
~~~~~~~~~~~~~~~~~~~~~~~~~~~

To be able to group the records, fluid will load every record itself and groups those afterwards.
If you plan to group many records just for getting something like a count, maybe it is better to fire the query directly and don't use fluid for that.

However if the result is on a cacheable page, the issue is only relevant on the first hit.
